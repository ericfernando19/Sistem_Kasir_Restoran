@extends('layouts.app')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ isset($user) ? 'Ubah detail pengguna' : 'Tambahkan pengguna baru' }}</p>
        </div>
        <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-maroon-600 transition-all">Kembali</a>
    </div>

    <form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 space-y-5">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('name') border-rose-300 @enderror" required>
            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('email') border-rose-300 @enderror" required>
            @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('password') border-rose-300 @enderror" {{ isset($user) ? '' : 'required' }}>
            @error('password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            @if(isset($user)) <p class="mt-1 text-xs text-gray-400">Kosongkan jika tidak ingin mengubah password</p> @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none" {{ isset($user) ? '' : 'required' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
            <select name="role" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('role') border-rose-300 @enderror" required>
                <option value="kasir" {{ old('role', $user->role ?? '') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none @error('phone') border-rose-300 @enderror">
            @error('phone') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        @if(isset($user))
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-maroon-600 focus:ring-maroon-500">
            <label for="is_active" class="text-sm text-gray-700">Akun aktif</label>
        </div>
        @endif

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-xl bg-maroon-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-maroon-800 shadow-sm transition-all">
                {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
            </button>
            <a href="{{ route('users.index') }}" class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection
