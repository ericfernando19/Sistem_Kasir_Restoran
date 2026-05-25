@extends('layouts.app')

@section('title', 'Menu Restoran')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Menu Restoran</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar menu makanan dan minuman</p>
    </div>
    <a href="{{ route('menus.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 shadow-sm transition-all">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Menu
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden">
    <div class="p-4 border-b border-cream-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu..." class="w-full rounded-xl border border-gray-200 px-4 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
            </div>
            <select name="category_id" class="rounded-xl border border-gray-200 px-4 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="is_available" class="rounded-xl border border-gray-200 px-4 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>Habis</option>
            </select>
            <button type="submit" class="rounded-xl bg-maroon-100 px-4 py-2 text-sm font-medium text-maroon-700 hover:bg-maroon-200 transition-all">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Foto</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kode</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Harga</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="h-10 w-10 rounded-lg bg-gray-100 overflow-hidden">
                            @if($menu->photo)
                            <img src="{{ asset('storage/' . $menu->photo) }}" alt="{{ $menu->name }}" class="h-full w-full object-cover">
                            @else
                            <div class="h-full w-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-500">{{ $menu->code }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $menu->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $menu->category?->name }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-maroon-600">Rp {{ number_format($menu->selling_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $menu->is_available ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('menus.edit', ['menu' => $menu->id, 'page' => request('page', 1)]) }}" class="rounded-lg bg-blue-50 p-2 text-blue-600 hover:bg-blue-100 transition-all" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('menus.toggle-availability', $menu->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                                <button type="submit" class="rounded-lg p-2 {{ $menu->is_available ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }} transition-all" title="{{ $menu->is_available ? 'Tandai Habis' : 'Tandai Tersedia' }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('menus.destroy', $menu->id) }}" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                                <button type="submit" class="rounded-lg bg-rose-50 p-2 text-rose-600 hover:bg-rose-100 transition-all" title="Hapus">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p class="text-sm text-gray-400">Belum ada menu</p>
                        <a href="{{ route('menus.create') }}" class="text-maroon-600 text-sm font-medium hover:underline mt-1 inline-block">Tambah menu pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($menus->hasPages())
    <div class="p-4 border-t border-cream-100">
        {{ $menus->links() }}
    </div>
    @endif
</div>
@endsection
