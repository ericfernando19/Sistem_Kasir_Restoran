<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const TAX_RATE = 10;

    public function index()
    {
        $orders = Transaction::with('user', 'table', 'items.product')
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->latest()
            ->get();

        $completedToday = Transaction::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();

        return view('orders.index', compact('orders', 'completedToday'));
    }

    public function create()
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_available', true)->where('is_active', true);
        }])->get();

        $tables = Table::where('status', 'available')->get();
        $cart = session()->get('cart', []);
        $selectedTable = session()->get('selected_table');

        $totals = !empty($cart) ? Transaction::calculateTotals($cart, self::TAX_RATE) : ['subtotal' => 0, 'tax' => 0, 'service_charge' => 0, 'grand_total' => 0];

        return view('orders.create', compact('categories', 'tables', 'cart', 'selectedTable', 'totals'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_available || !$product->is_active) {
            return response()->json(['error' => 'Menu tidak tersedia.'], 422);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['quantity'] * $product->selling_price;
            if ($request->notes) {
                $cart[$product->id]['notes'] = $request->notes;
            }
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'price' => (float) $product->selling_price,
                'quantity' => $request->quantity,
                'notes' => $request->notes ?? '',
                'photo' => $product->photo,
                'subtotal' => $request->quantity * (float) $product->selling_price,
            ];
        }

        session()->put('cart', $cart);

        $totals = Transaction::calculateTotals($cart, self::TAX_RATE);

        return response()->json([
            'cart' => array_values($cart),
            'totals' => $totals,
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$request->product_id]);
        } elseif (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            $cart[$request->product_id]['subtotal'] = $request->quantity * $cart[$request->product_id]['price'];
            if ($request->filled('notes')) {
                $cart[$request->product_id]['notes'] = $request->notes;
            }
        }

        session()->put('cart', $cart);

        $totals = !empty($cart) ? Transaction::calculateTotals($cart, self::TAX_RATE) : null;

        return response()->json([
            'cart' => array_values($cart),
            'totals' => $totals,
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        $totals = !empty($cart) ? Transaction::calculateTotals($cart, self::TAX_RATE) : null;

        return response()->json([
            'cart' => array_values($cart),
            'totals' => $totals,
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang pesanan kosong.');
        }

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment_amount' => 'required|numeric|min:0',
            'table_id' => 'required|exists:tables,id',
        ]);

        $selectedTable = $validated['table_id'];

        $totals = Transaction::calculateTotals($cart, self::TAX_RATE);

        if ((float) $validated['payment_amount'] < $totals['grand_total']) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total belanja.');
        }

        DB::beginTransaction();
        try {
            $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.str_pad(Transaction::max('id') + 1 ?? 1, 4, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(),
                'table_id' => $selectedTable,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'total' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'grand_total' => $totals['grand_total'],
                'payment_amount' => $validated['payment_amount'],
                'change_amount' => $validated['payment_amount'] - $totals['grand_total'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'notes' => $item['notes'] ?? null,
                    'subtotal' => $item['subtotal'],
                ]);
            }

            Table::where('id', $selectedTable)->update(['status' => 'occupied']);

            session()->forget('cart');
            session()->forget('selected_table');

            DB::commit();

            return redirect()->route('orders.receipt', $transaction->id)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function selectTable(Request $request)
    {
        $request->validate(['table_id' => 'required|exists:tables,id']);
        session()->put('selected_table', $request->table_id);

        return response()->json(['success' => true]);
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('items.product', 'user', 'table');

        return view('orders.receipt', compact('transaction'));
    }

    public function clearCart()
    {
        session()->forget('cart');
        session()->forget('selected_table');

        return redirect()->route('orders.create');
    }

    public function history(Request $request)
    {
        $query = Transaction::with('user', 'table', 'items.product');

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }

        $orders = $query->latest()->paginate(15);

        $totalRevenue = Transaction::where('status', 'completed')->sum('grand_total');
        $totalOrders = Transaction::count();
        $totalCancelled = Transaction::where('status', 'cancelled')->count();

        return view('orders.history', compact(
            'orders', 'totalRevenue', 'totalOrders', 'totalCancelled'
        ));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('items.product', 'user', 'table');

        return view('orders.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $validated['status'];

        $transaction->update(['status' => $newStatus]);

        if ($newStatus === 'completed' && $transaction->table_id) {
            Table::where('id', $transaction->table_id)->update(['status' => 'available']);
        }

        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled' && $transaction->table_id) {
            Table::where('id', $transaction->table_id)->update(['status' => 'available']);
        }

        $statusLabels = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'ready' => 'Siap diantar',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return back()->with('success', "Status pesanan diubah menjadi {$statusLabels[$newStatus]}.");
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->update(['status' => 'cancelled']);

        if ($transaction->table_id) {
            Table::where('id', $transaction->table_id)->update(['status' => 'available']);
        }

        return redirect()->route('orders.history')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
