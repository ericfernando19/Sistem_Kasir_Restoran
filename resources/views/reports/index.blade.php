@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <select name="period" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
            <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun Ini</option>
            <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Kustom</option>
        </select>
        <div id="custom-dates" class="flex gap-2 {{ $period === 'custom' ? '' : 'hidden' }}">
            <input type="date" name="date_from" value="{{ request('date_from', $startDate->format('Y-m-d')) }}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
            <input type="date" name="date_to" value="{{ request('date_to', $endDate->format('Y-m-d')) }}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
        </div>
        <button type="submit" class="rounded-xl bg-maroon-700 px-4 py-2 text-sm font-semibold text-white hover:bg-maroon-800 transition-all">Tampilkan</button>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Pendapatan</p>
        <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Transaksi</p>
        <p class="text-lg font-bold text-gray-900">{{ $transactionCount }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Dibatalkan</p>
        <p class="text-lg font-bold text-rose-600">{{ $cancelledCount }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Pending</p>
        <p class="text-lg font-bold text-amber-600">{{ $pendingCount }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
        <p class="text-xs text-gray-500">Rata-rata</p>
        <p class="text-lg font-bold text-blue-600">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Menu Terlaris</h3>
        @if($topMenus->count() > 0)
            <div class="space-y-3">
                @foreach($topMenus as $index => $menu)
                <div class="flex items-center gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold {{ $index === 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
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
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
        @endif
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Statistik Pembayaran</h3>
        <div class="space-y-3">
            @foreach($paymentMethodStats as $stat)
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 capitalize">{{ $stat->payment_method === 'cash' ? 'Tunai' : ($stat->payment_method === 'transfer' ? 'Transfer' : 'QRIS') }}</span>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ $stat->count }}x</span>
                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($stat->total, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <h3 class="text-base font-semibold text-gray-900 mt-6 mb-4">Statistik Status</h3>
        <div class="space-y-3">
            @foreach($statusStats as $stat)
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 capitalize">{{ $stat->status === 'pending' ? 'Menunggu' : ($stat->status === 'processing' ? 'Diproses' : ($stat->status === 'ready' ? 'Siap' : ($stat->status === 'completed' ? 'Selesai' : 'Batal'))) }}</span>
                <span class="text-sm font-semibold text-gray-900">{{ $stat->count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200 mb-6">
    <h3 class="text-base font-semibold text-gray-900 mb-4">Kinerja Kasir</h3>
    @if($kasirPerformance->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100">
                    <th class="py-2 font-medium text-gray-500">Kasir</th>
                    <th class="py-2 font-medium text-gray-500 text-right">Transaksi</th>
                    <th class="py-2 font-medium text-gray-500 text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($kasirPerformance as $kasir)
                <tr>
                    <td class="py-2 font-medium text-gray-900">{{ $kasir->name }}</td>
                    <td class="py-2 text-right text-gray-600">{{ $kasir->total_transactions }}</td>
                    <td class="py-2 text-right font-semibold text-maroon-600">Rp {{ number_format($kasir->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
    @endif
</div>

<div class="bg-white rounded-2xl p-5 shadow-sm border border-cream-200">
    <h3 class="text-base font-semibold text-gray-900 mb-4">Penjualan Harian</h3>
    @if($dailyRevenue->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100">
                    <th class="py-2 font-medium text-gray-500">Tanggal</th>
                    <th class="py-2 font-medium text-gray-500 text-right">Transaksi</th>
                    <th class="py-2 font-medium text-gray-500 text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($dailyRevenue as $day)
                <tr>
                    <td class="py-2 text-gray-900">{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                    <td class="py-2 text-right text-gray-600">{{ $day->count }}</td>
                    <td class="py-2 text-right font-semibold text-maroon-600">Rp {{ number_format($day->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-sm text-gray-400 text-center py-6">Belum ada data penjualan</p>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelector('[name="period"]')?.addEventListener('change', function() {
    const customDates = document.getElementById('custom-dates');
    customDates.style.display = this.value === 'custom' ? 'flex' : 'none';
});
</script>
@endpush
