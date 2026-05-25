<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');

        $todayOrders = Transaction::whereDate('created_at', $today)->count();
        $todayRevenue = Transaction::whereDate('created_at', $today)->where('status', 'completed')->sum('grand_total');

        $monthRevenue = Transaction::whereDate('created_at', '>=', $startOfMonth)
            ->where('status', 'completed')
            ->sum('grand_total');

        $totalMenus = Product::where('is_active', true)->count();
        $totalCategories = Category::count();

        $pendingOrders = Transaction::where('status', 'pending')->count();
        $processingOrders = Transaction::where('status', 'processing')->count();

        $recentOrders = Transaction::with('user', 'table')
            ->latest()
            ->take(10)
            ->get();

        $topMenus = TransactionItem::select(
            'products.name',
            DB::raw('SUM(transaction_items.quantity) as total_qty'),
            DB::raw('SUM(transaction_items.subtotal) as total_revenue')
        )
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->whereDate('transactions.created_at', '>=', now()->subDays(30))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'todayOrders', 'todayRevenue', 'monthRevenue', 'totalMenus', 'totalCategories',
            'pendingOrders', 'processingOrders', 'recentOrders', 'topMenus'
        ));
    }
}
