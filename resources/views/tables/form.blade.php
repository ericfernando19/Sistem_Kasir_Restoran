@extends('layouts.app')

@section('title', isset($table) ? 'Edit Meja' : 'Tambah Meja')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ isset($table) ? 'Edit Meja' : 'Tambah Meja' }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ isset($table) ? 'Ubah detail meja' : 'Tambahkan meja baru' }}</p>
        </div>
        <a href="{{ route('tables.index') }}" class="text-sm text-gray-500 hover:text-maroon-600 transition-all">Kembali</a>
    </div>

    <form method="POST" action="{{ isset($table) ? route('tables.update', $table->id) : route('tables.store') }}" class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 space-y-5">
        @csrf
        @if(isset($table)) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Meja</label>
            <input type="text" name="table_number" value="{{ old('table_number', $table->table_number ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('table_number') border-rose-300 @enderror" required placeholder="Contoh: A1, B2, 5">
            @error('table_number') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kapasitas (orang)</label>
            <input type="number" name="capacity" value="{{ old('capacity', $table->capacity ?? 4) }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('capacity') border-rose-300 @enderror" required min="1" max="50">
            @error('capacity') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-xl bg-maroon-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 shadow-sm transition-all">
                {{ isset($table) ? 'Simpan Perubahan' : 'Tambah Meja' }}
            </button>
            <a href="{{ route('tables.index') }}" class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection
