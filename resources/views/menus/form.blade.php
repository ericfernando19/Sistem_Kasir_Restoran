@extends('layouts.app')

@section('title', isset($menu) ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ isset($menu) ? 'Edit Menu' : 'Tambah Menu' }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ isset($menu) ? 'Ubah detail menu' : 'Tambahkan menu baru ke restoran' }}</p>
        </div>
        <a href="{{ route('menus.index') }}" class="text-sm text-gray-500 hover:text-maroon-600 transition-all">Kembali</a>
    </div>

    <form method="POST" action="{{ isset($menu) ? route('menus.update', $menu->id) : route('menus.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 space-y-5">
        @csrf
        @if(isset($menu)) @method('PUT') @endif
        @if(isset($menu)) <input type="hidden" name="page" value="{{ request('page', 1) }}"> @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Menu</label>
                <input type="text" name="code" value="{{ old('code', $menu->code ?? $nextCode) }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('code') border-rose-300 @enderror" required>
                @error('code') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                <select name="category_id" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('category_id') border-rose-300 @enderror" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Menu</label>
            <input type="text" name="name" value="{{ old('name', $menu->name ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('name') border-rose-300 @enderror" required>
            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('description') border-rose-300 @enderror">{{ old('description', $menu->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp)</label>
            <input type="number" name="selling_price" value="{{ old('selling_price', $menu->selling_price ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('selling_price') border-rose-300 @enderror" required min="0">
            @error('selling_price') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Menu</label>
            <input type="file" name="photo" accept="image/*" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-maroon-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-maroon-700 hover:file:bg-maroon-100 focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('photo') border-rose-300 @enderror">
            @error('photo') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            @if(isset($menu) && $menu->photo)
            <div class="mt-2 flex items-center gap-2">
                <img src="{{ asset('storage/' . $menu->photo) }}" alt="{{ $menu->name }}" class="h-16 w-16 rounded-lg object-cover">
                <span class="text-xs text-gray-400">Foto saat ini</span>
            </div>
            @endif
        </div>

        @if(isset($menu))
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-maroon-600 focus:ring-maroon-500">
            <label for="is_available" class="text-sm text-gray-700">Menu tersedia</label>
        </div>
        @endif

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-xl bg-maroon-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 shadow-sm transition-all">
                {{ isset($menu) ? 'Simpan Perubahan' : 'Tambah Menu' }}
            </button>
            <a href="{{ route('menus.index') }}" class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection
