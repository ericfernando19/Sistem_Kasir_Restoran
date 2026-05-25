<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user', 'items.product');

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

        $transactions = $query->latest()->paginate(15);

        $totalRevenue = Transaction::where('status', 'completed')->sum('total');
        $totalTransactions = Transaction::count();
        $totalCancelled = Transaction::where('status', 'cancelled')->count();

        return view('transactions.history', compact(
            'transactions', 'totalRevenue', 'totalTransactions', 'totalCancelled'
        ));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('items.product', 'user');

        return view('transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->update(['status' => 'cancelled']);

        foreach ($transaction->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        return redirect()->route('transactions.history')
            ->with('success', 'Transaksi berhasil dibatalkan, stok dikembalikan.');
    }
}
