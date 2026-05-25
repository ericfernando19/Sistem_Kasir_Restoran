@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard Restoran</h1>
    <p class="text-sm text-gray-500 mt-1">Ringkasan bisnis {{ now()->format('d F Y') }}</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-maroon-100 text-maroon-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <span class="text-xs font-medium text-gray-400">Hari Ini</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $todayOrders }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Pesanan</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-gray-400">Hari Ini</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-500 mt-1">Pendapatan</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            </div>
            <span class="text-xs font-medium text-gray-400">Total</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $totalMenus }}</p>
        <p class="text-sm text-gray-500 mt-1">Menu Tersedia</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <span class="text-xs font-medium text-gray-400">Bulan Ini</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-500 mt-1">Pendapatan Bulanan</p>
    </div>
</div>

<div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200 mb-8">
    <h3 class="text-base font-semibold text-gray-900 mb-4">Menu Terlaris</h3>
        @if($topMenus->count() > 0)
            <div class="space-y-3">
                @foreach($topMenus as $index => $menu)
                <div class="flex items-center gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-gray-100 text-gray-500' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-cream-100 text-cream-700')) }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $menu->name }}</p>
                        <p class="text-xs text-gray-500">{{ $menu->total_qty }} terjual</p>
                    </div>
                    <span class="text-sm font-semibold text-maroon-600">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="text-sm text-gray-400">Belum ada data penjualan</p>
            </div>
        @endif
    </div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-900">Pesanan Aktif</h3>
            <div class="flex gap-2">
                @if($pendingOrders > 0)
                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ $pendingOrders }} Menunggu</span>
                @endif
                @if($processingOrders > 0)
                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">{{ $processingOrders }} Diproses</span>
                @endif
            </div>
        </div>
        @if($recentOrders->whereIn('status', ['pending', 'processing', 'ready'])->count() > 0)
            <div class="space-y-2">
                @foreach($recentOrders->whereIn('status', ['pending', 'processing', 'ready']) as $order)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-sm font-semibold text-gray-700">#{{ $order->invoice_number }}</span>
                        @if($order->table)
                        <span class="text-xs text-gray-500">Meja {{ $order->table->table_number }}</span>
                        @endif
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status === 'ready' ? 'bg-emerald-100 text-emerald-800' : '' }}">
                        {{ $order->status === 'pending' ? 'Menunggu' : ($order->status === 'processing' ? 'Diproses' : 'Siap diantar') }}
                    </span>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm text-gray-400">Tidak ada pesanan aktif</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Pesanan Terbaru</h3>
        @if($recentOrders->count() > 0)
            <div class="space-y-2">
                @foreach($recentOrders as $order)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-sm font-medium text-gray-900 truncate">#{{ $order->invoice_number }}</span>
                        @if($order->table)
                        <span class="text-xs text-gray-500">Meja {{ $order->table->table_number }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-sm font-semibold text-maroon-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $order->status === 'completed' ? 'bg-gray-100 text-gray-700' : '' }}
                            {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'ready' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                            {{ $order->status === 'pending' ? 'Menunggu' : ($order->status === 'processing' ? 'Diproses' : ($order->status === 'ready' ? 'Siap' : ($order->status === 'completed' ? 'Selesai' : 'Batal'))) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm text-gray-400">Belum ada transaksi</p>
            </div>
        @endif
    </div>
</div>
@endsection
