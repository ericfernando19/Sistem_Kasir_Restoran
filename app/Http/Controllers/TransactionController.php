<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        }])->get();

        $cart = session()->get('cart', []);

        return view('transactions.index', compact('categories', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json([
                'error' => "Stok tidak mencukupi. Sisa stok: {$product->stock}",
            ], 422);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $newQty = $cart[$product->id]['quantity'] + $request->quantity;
            if ($newQty > $product->stock) {
                return response()->json([
                    'error' => "Stok tidak mencukupi. Sisa stok: {$product->stock}",
                ], 422);
            }
            $cart[$product->id]['quantity'] = $newQty;
            $cart[$product->id]['subtotal'] = $newQty * $product->selling_price;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'price' => (float) $product->selling_price,
                'quantity' => $request->quantity,
                'stock' => $product->stock,
                'photo' => $product->photo,
                'subtotal' => $request->quantity * (float) $product->selling_price,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'cart' => array_values($cart),
            'total' => $this->calculateTotal($cart),
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$request->product_id]);
        } elseif (isset($cart[$request->product_id])) {
            $product = Product::find($request->product_id);
            if ($request->quantity > $product->stock) {
                return response()->json([
                    'error' => "Stok tidak mencukupi. Sisa stok: {$product->stock}",
                ], 422);
            }
            $cart[$request->product_id]['quantity'] = $request->quantity;
            $cart[$request->product_id]['subtotal'] = $request->quantity * $cart[$request->product_id]['price'];
        }

        session()->put('cart', $cart);

        return response()->json([
            'cart' => array_values($cart),
            'total' => $this->calculateTotal($cart),
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        return response()->json([
            'cart' => array_values($cart),
            'total' => $this->calculateTotal($cart),
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        $total = $this->calculateTotal($cart);

        if ((float) $validated['payment_amount'] < $total) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total belanja.');
        }

        DB::beginTransaction();
        try {
            $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.str_pad(Transaction::max('id') + 1 ?? 1, 4, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'total' => $total,
                'payment_amount' => $validated['payment_amount'],
                'change_amount' => $validated['payment_amount'] - $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
            ]);

            foreach ($cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');

            DB::commit();

            return redirect()->route('transactions.receipt', $transaction->id)
                ->with('success', 'Transaksi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('items.product', 'user');

        return view('transactions.receipt', compact('transaction'));
    }

    public function clearCart()
    {
        session()->forget('cart');

        return redirect()->route('transactions.index');
    }

    private function calculateTotal(array $cart): float
    {
        return array_sum(array_column($cart, 'subtotal'));
    }
}
