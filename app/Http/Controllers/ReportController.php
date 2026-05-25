<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'month';

        $startDate = match ($period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            'custom' => $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth(),
            default => now()->startOfMonth(),
        };

        $endDate = match ($period) {
            'today' => now()->endOfDay(),
            'week' => now()->endOfWeek(),
            'month' => now()->endOfMonth(),
            'year' => now()->endOfYear(),
            'custom' => $request->date_to ? Carbon::parse($request->date_to) : now()->endOfDay(),
            default => now()->endOfMonth(),
        };

        $baseQuery = Transaction::whereBetween('created_at', [$startDate, $endDate]);

        $revenue = (clone $baseQuery)->where('status', 'completed')->sum('grand_total');
        $transactionCount = (clone $baseQuery)->where('status', 'completed')->count();
        $cancelledCount = (clone $baseQuery)->where('status', 'cancelled')->count();
        $pendingCount = (clone $baseQuery)->whereIn('status', ['pending', 'processing', 'ready'])->count();
        $avgTransaction = $transactionCount > 0 ? $revenue / $transactionCount : 0;

        $dailyRevenue = (clone $baseQuery)->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total'),
            DB::raw('COUNT(*) as count')
        )
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topMenus = TransactionItem::select(
            'products.name',
            DB::raw('SUM(transaction_items.quantity) as total_qty'),
            DB::raw('SUM(transaction_items.subtotal) as total_revenue')
        )
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        $kasirPerformance = Transaction::select(
            'users.name',
            DB::raw('COUNT(*) as total_transactions'),
            DB::raw('SUM(grand_total) as total_revenue')
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_revenue')
            ->get();

        $paymentMethodStats = (clone $baseQuery)->select(
            'payment_method',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(grand_total) as total')
        )
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        $statusStats = (clone $baseQuery)->select(
            'status',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('status')
            ->get();

        return view('reports.index', compact(
            'revenue', 'transactionCount', 'cancelledCount', 'pendingCount', 'avgTransaction',
            'dailyRevenue', 'topMenus', 'kasirPerformance', 'paymentMethodStats', 'statusStats',
            'period', 'startDate', 'endDate'
        ));
    }
}
