@extends('layouts.app')

@section('title', 'Struk Pembayaran')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden">
        <div class="p-6 text-center border-b border-cream-100 no-print">
            <h1 class="text-xl font-bold text-gray-900">Pesanan Berhasil!</h1>
            <p class="text-sm text-gray-500 mt-1">Pesanan telah dicatat dan sedang diproses</p>
        </div>

        <div class="p-6" id="receipt">
            <div class="text-center mb-4">
                <h2 class="text-lg font-bold text-gray-900">Restoran KasirKu</h2>
                <p class="text-xs text-gray-500">Jl. Contoh No. 123, Kota</p>
                <p class="text-xs text-gray-500">Telp: 0812-3456-7890</p>
            </div>

            <div class="border-t border-dashed border-gray-300 my-3"></div>

            <div class="text-xs text-gray-600 space-y-1 mb-3">
                <div class="flex justify-between">
                    <span>No. Invoice</span>
                    <span class="font-semibold">{{ $transaction->invoice_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal</span>
                    <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span>
                    <span>{{ $transaction->user->name }}</span>
                </div>
                @if($transaction->table)
                <div class="flex justify-between">
                    <span>Meja</span>
                    <span>{{ $transaction->table->table_number }}</span>
                </div>
                @endif
                @if($transaction->customer_name)
                <div class="flex justify-between">
                    <span>Pelanggan</span>
                    <span>{{ $transaction->customer_name }}</span>
                </div>
                @endif
            </div>

            <div class="border-t border-dashed border-gray-300 my-3"></div>

            <table class="w-full text-xs mb-3">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-left py-1">Item</th>
                        <th class="text-center py-1">Qty</th>
                        <th class="text-right py-1">Harga</th>
                        <th class="text-right py-1">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="py-1 text-gray-900">
                            {{ $item->product->name }}
                            @if($item->notes)
                            <br><span class="text-[10px] text-amber-600 italic">{{ $item->notes }}</span>
                            @endif
                        </td>
                        <td class="py-1 text-center text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-1 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-1 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t border-dashed border-gray-300 my-3"></div>

            <div class="text-xs space-y-1">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Pajak (10%)</span>
                    <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-gray-900 pt-2 border-t border-gray-200">
                    <span>Grand Total</span>
                    <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600 pt-1">
                    <span>Pembayaran</span>
                    <span class="capitalize">{{ $transaction->payment_method === 'cash' ? 'Tunai' : ($transaction->payment_method === 'transfer' ? 'Transfer' : 'QRIS') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Jumlah Bayar</span>
                    <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold text-emerald-600">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-300 my-4"></div>

            <div class="text-center text-xs text-gray-500">
                <p>Terima kasih telah berbelanja!</p>
                <p class="mt-1">Selamat menikmati hidangan</p>
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-cream-100 flex gap-3 no-print">
            <button onclick="window.print()" class="flex-1 rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 transition-all text-center">
                Cetak Struk
            </button>
            <a href="{{ route('orders.create') }}" class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all text-center">
                Pesanan Baru
            </a>
            <a href="{{ route('orders.index') }}" class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all text-center">
                Pesanan Aktif
            </a>
        </div>
    </div>
</div>
@endsection
