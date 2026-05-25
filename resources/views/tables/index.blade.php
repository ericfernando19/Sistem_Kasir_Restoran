@extends('layouts.app')

@section('title', 'Manajemen Meja')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Meja</h1>
        <p class="text-sm text-gray-500 mt-1">Atur meja restoran</p>
    </div>
    <a href="{{ route('tables.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 shadow-sm transition-all">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Meja
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden">
    <div class="p-4 border-b border-cream-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari meja..." class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
            <select name="status" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Terisi</option>
                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Dipesan</option>
            </select>
            <button type="submit" class="rounded-xl bg-maroon-100 px-4 py-2 text-sm font-medium text-maroon-700 hover:bg-maroon-200 transition-all">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Meja</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kapasitas</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tables as $table)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">Meja {{ $table->table_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $table->capacity }} orang</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $table->status === 'available' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $table->status === 'occupied' ? 'bg-rose-100 text-rose-800' : '' }}
                            {{ $table->status === 'reserved' ? 'bg-amber-100 text-amber-800' : '' }}">
                            {{ $table->status === 'available' ? 'Tersedia' : ($table->status === 'occupied' ? 'Terisi' : 'Dipesan') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('tables.edit', $table->id) }}" class="rounded-lg bg-blue-50 p-2 text-blue-600 hover:bg-blue-100 transition-all" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('tables.toggle-status', $table->id) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="rounded-lg p-2 {{ $table->status === 'available' ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }} transition-all" title="{{ $table->status === 'available' ? 'Tandai Dipesan' : 'Tandai Tersedia' }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('tables.destroy', $table->id) }}" onsubmit="return confirm('Hapus meja {{ $table->table_number }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg bg-rose-50 p-2 text-rose-600 hover:bg-rose-100 transition-all" title="Hapus">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center">
                        <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <p class="text-sm text-gray-400">Belum ada meja</p>
                        <a href="{{ route('tables.create') }}" class="text-maroon-600 text-sm font-medium hover:underline mt-1 inline-block">Tambah meja pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tables->hasPages())
    <div class="p-4 border-t border-cream-100">
        {{ $tables->links() }}
    </div>
    @endif
</div>
@endsection
