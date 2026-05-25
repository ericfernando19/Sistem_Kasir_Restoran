@extends('layouts.app')

@section('title', 'Kategori Menu')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kategori Menu</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola kategori menu restoran</p>
    </div>
    <button @@click="$dispatch('open-modal', 'create-category')" class="inline-flex items-center gap-2 rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 shadow-sm transition-all">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Kategori
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Kategori</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Jumlah Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $category->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $category->products_count }} menu</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <button @@click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')" class="rounded-lg bg-blue-50 p-2 text-blue-600 hover:bg-blue-100 transition-all" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('categories.destroy', $category->id) }}" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg bg-rose-50 p-2 text-rose-600 hover:bg-rose-100 transition-all" title="Hapus">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div x-data="{ open: false }" x-on:open-modal.window="if ($event.detail === 'edit-category-{{ $category->id }}') open = true" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
                    <div x-show="open" @@click="open = false" class="fixed inset-0 bg-black/40"></div>
                    <div x-show="open" @@click.outside="open = false" class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 z-10">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Kategori</h3>
                        <form method="POST" action="{{ route('categories.update', $category->id) }}">
                            @csrf @method('PUT')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kategori</label>
                                <input type="text" name="name" value="{{ $category->name }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                                <textarea name="description" rows="2" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">{{ $category->description }}</textarea>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="submit" class="rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 transition-all">Simpan</button>
                                <button type="button" @@click="open = false" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center">
                        <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        <p class="text-sm text-gray-400">Belum ada kategori</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="p-4 border-t border-cream-100">
        {{ $categories->links() }}
    </div>
    @endif
</div>

{{-- Create Modal --}}
<div x-data="{ open: false }" x-on:open-modal.window="if ($event.detail === 'create-category') open = true" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
    <div x-show="open" @@click="open = false" class="fixed inset-0 bg-black/40"></div>
    <div x-show="open" @@click.outside="open = false" class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 z-10">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Kategori</h3>
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kategori</label>
                <input type="text" name="name" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none" required placeholder="Contoh: Makanan, Minuman">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none" placeholder="Opsional"></textarea>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 transition-all">Simpan</button>
                <button type="button" @@click="open = false" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection
