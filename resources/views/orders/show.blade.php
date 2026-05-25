@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap pesanan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('orders.receipt', $transaction->id) }}" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Cetak Ulang</a>
            <a href="{{ route('orders.history') }}" class="text-sm text-gray-500 hover:text-maroon-600 transition-all">Kembali</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden">
        <div class="p-6 border-b border-cream-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">#{{ $transaction->invoice_number }}</h2>
                    <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d F Y H:i') }}</p>
                </div>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                    {{ $transaction->status === 'completed' ? 'bg-gray-100 text-gray-700' : '' }}
                    {{ $transaction->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                    {{ $transaction->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $transaction->status === 'ready' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $transaction->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                    {{ $transaction->status === 'pending' ? 'Menunggu' : ($transaction->status === 'processing' ? 'Diproses' : ($transaction->status === 'ready' ? 'Siap diantar' : ($transaction->status === 'completed' ? 'Selesai' : 'Dibatalkan'))) }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Kasir</span>
                    <p class="font-medium text-gray-900">{{ $transaction->user->name }}</p>
                </div>
                @if($transaction->table)
                <div>
                    <span class="text-gray-500">Meja</span>
                    <p class="font-medium text-gray-900">{{ $transaction->table->table_number }}</p>
                </div>
                @endif
                @if($transaction->customer_name)
                <div>
                    <span class="text-gray-500">Pelanggan</span>
                    <p class="font-medium text-gray-900">{{ $transaction->customer_name }}</p>
                </div>
                @endif
                <div>
                    <span class="text-gray-500">Pembayaran</span>
                    <p class="font-medium text-gray-900 capitalize">{{ $transaction->payment_method === 'cash' ? 'Tunai' : ($transaction->payment_method === 'transfer' ? 'Transfer' : 'QRIS') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Item Pesanan</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-100">
                        <th class="text-left py-2 font-medium">Menu</th>
                        <th class="text-center py-2 font-medium">Qty</th>
                        <th class="text-right py-2 font-medium">Harga</th>
                        <th class="text-right py-2 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="py-3">
                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                            @if($item->notes)
                            <p class="text-xs text-amber-600 italic">{{ $item->notes }}</p>
                            @endif
                        </td>
                        <td class="py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-3 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-3 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-gray-50 border-t border-cream-100">
            <div class="max-w-xs ml-auto space-y-1.5 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Pajak (10%)</span>
                    <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
                    <span>Grand Total</span>
                    <span class="text-maroon-700">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600 pt-1">
                    <span>Dibayar</span>
                    <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold text-emerald-600">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if(in_array($transaction->status, ['pending', 'processing', 'ready']))
        <div class="p-4 border-t border-cream-100 flex gap-2">
            @if($transaction->status === 'pending')
            <form method="POST" action="{{ route('orders.update-status', $transaction->id) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="processing">
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-all">Proses Pesanan</button>
            </form>
            @endif
            @if($transaction->status === 'processing')
            <form method="POST" action="{{ route('orders.update-status', $transaction->id) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="ready">
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-all">Tandai Siap</button>
            </form>
            @endif
            @if($transaction->status === 'ready')
            <form method="POST" action="{{ route('orders.update-status', $transaction->id) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="rounded-xl bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900 transition-all">Selesaikan Pesanan</button>
            </form>
            @endif
            @if(in_array($transaction->status, ['pending', 'processing']))
            <form method="POST" action="{{ route('orders.destroy', $transaction->id) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-xl bg-rose-100 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-200 transition-all">Batalkan Pesanan</button>
            </form>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
