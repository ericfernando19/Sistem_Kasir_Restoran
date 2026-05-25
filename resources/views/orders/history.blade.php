@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Pesanan</h1>
        <p class="text-sm text-gray-500 mt-1">Lihat semua transaksi dan pesanan</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Total Pesanan</p>
        <p class="text-xl font-bold text-gray-900">{{ $totalOrders }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Pendapatan</p>
        <p class="text-xl font-bold text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Dibatalkan</p>
        <p class="text-xl font-bold text-rose-600">{{ $totalCancelled }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Rata-rata</p>
        <p class="text-xl font-bold text-blue-600">Rp {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : 0 }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden">
    <div class="p-4 border-b border-cream-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
            <select name="payment_method" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
                <option value="">Semua Pembayaran</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
            </select>
            <select name="status" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Siap</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Invoice..." class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
            <button type="submit" class="rounded-xl bg-maroon-100 px-4 py-2 text-sm font-medium text-maroon-700 hover:bg-maroon-200 transition-all">Filter</button>
            <a href="{{ route('orders.history') }}" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Reset</a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Invoice</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Meja</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Pembayaran</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-mono font-medium text-gray-900">{{ $order->invoice_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $order->table?->table_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $order->user->name }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-maroon-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm capitalize">{{ $order->payment_method === 'cash' ? 'Tunai' : ($order->payment_method === 'transfer' ? 'Transfer' : 'QRIS') }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $order->status === 'completed' ? 'bg-gray-100 text-gray-700' : '' }}
                            {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'ready' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                            {{ $order->status === 'pending' ? 'Menunggu' : ($order->status === 'processing' ? 'Diproses' : ($order->status === 'ready' ? 'Siap' : ($order->status === 'completed' ? 'Selesai' : 'Batal'))) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('orders.show', $order->id) }}" class="rounded-lg bg-maroon-50 px-3 py-1.5 text-xs font-medium text-maroon-700 hover:bg-maroon-100 transition-all">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-sm text-gray-400">Belum ada transaksi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="p-4 border-t border-cream-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
