@extends('layouts.app')

@section('title', 'Pesanan Aktif')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pesanan Aktif</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor pesanan yang sedang berjalan</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">Selesai hari ini: <strong class="text-maroon-600">{{ $completedToday }}</strong></span>
        <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 shadow-sm transition-all">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Pesanan Baru
        </a>
    </div>
</div>

@if($orders->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($orders as $order)
    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-4 border-b border-cream-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-900">#{{ $order->invoice_number }}</span>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                    {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->status === 'ready' ? 'bg-emerald-100 text-emerald-800' : '' }}">
                    {{ $order->status === 'pending' ? 'Menunggu' : ($order->status === 'processing' ? 'Diproses' : 'Siap diantar') }}
                </span>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-500">
                @if($order->table)
                <span class="flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Meja {{ $order->table->table_number }}
                </span>
                @endif
                <span>{{ $order->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="p-4 space-y-2">
            @foreach($order->items as $item)
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 rounded-md px-1.5 py-0.5">{{ $item->quantity }}x</span>
                    <span class="text-gray-700 truncate">{{ $item->product->name }}</span>
                    @if($item->notes)
                    <span class="text-[10px] text-amber-600 bg-amber-50 rounded px-1 py-0.5 truncate max-w-[100px]">{{ $item->notes }}</span>
                    @endif
                </div>
                <span class="text-gray-500 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="p-4 bg-gray-50 border-t border-cream-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-900">Total</span>
                <span class="text-lg font-bold text-maroon-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
            <div class="flex gap-2">
                @if($order->status === 'pending')
                <form method="POST" action="{{ route('orders.update-status', $order->id) }}" class="flex-1">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="processing">
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition-all">Proses</button>
                </form>
                @endif
                @if($order->status === 'processing')
                <form method="POST" action="{{ route('orders.update-status', $order->id) }}" class="flex-1">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="ready">
                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition-all">Siap Antar</button>
                </form>
                @endif
                @if($order->status === 'ready')
                <form method="POST" action="{{ route('orders.update-status', $order->id) }}" class="flex-1">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="w-full rounded-xl bg-gray-800 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-900 transition-all">Selesai</button>
                </form>
                @endif
                @if(in_array($order->status, ['pending', 'processing']))
                <form method="POST" action="{{ route('orders.destroy', $order->id) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-xl bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-200 transition-all">Batal</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-12 text-center">
    <svg class="h-16 w-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    <p class="text-gray-500 font-medium">Tidak ada pesanan aktif</p>
    <p class="text-sm text-gray-400 mt-1">Semua pesanan telah selesai diproses</p>
    <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-2 mt-4 rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 transition-all">Buat Pesanan Baru</a>
</div>
@endif
@endsection
