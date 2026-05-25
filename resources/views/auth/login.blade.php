<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - {{ config('app.name', 'Restoran Kasir') }}</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-dvh flex items-center justify-center p-4 sm:p-6 relative overflow-y-auto"
      style="background: linear-gradient(135deg, #3b0b0f 0%, #7B1F24 40%, #a82432 100%);">

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-[500px] h-[500px] rounded-full bg-cream-300/10 -top-32 -right-32 blur-[100px]"></div>
        <div class="absolute w-[400px] h-[400px] rounded-full bg-cream-400/10 -bottom-32 -left-32 blur-[100px]"></div>
        <div class="absolute w-[300px] h-[300px] rounded-full bg-white/5 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 blur-[120px]"></div>
    </div>

    <div class="w-full max-w-md relative z-10" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
        <div class="text-center mb-8"
             x-show="loaded"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:enter-duration.500ms>
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-xl shadow-2xl mb-5 shadow-black/10 ring-1 ring-white/20">
                <svg class="w-8 h-8 text-cream-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Kasir<span class="text-cream-300">Ku</span></h1>
            <p class="text-cream-200/70 mt-2 text-sm font-medium tracking-wide">Sistem POS Restoran Modern</p>
        </div>

        <div class="bg-white/95 backdrop-blur-2xl rounded-2xl shadow-2xl shadow-black/30 p-8 ring-1 ring-white/20"
             x-show="loaded"
             x-transition:enter-start="opacity-0 translate-y-6"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:enter-duration.500ms
             x-transition:enter-delay.150ms>

            <div class="text-center mb-7">
                <h2 class="text-xl font-bold text-gray-900">Selamat Datang</h2>
                <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-maroon-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-maroon-400 focus:bg-white focus:ring-2 focus:ring-maroon-100 outline-none transition-all duration-200 @error('email') border-rose-300 bg-rose-50/50 focus:border-rose-400 focus:ring-rose-100 @enderror"
                               placeholder="admin@restoran.test" required autofocus>
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-maroon-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password"
                               class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-maroon-400 focus:bg-white focus:ring-2 focus:ring-maroon-100 outline-none transition-all duration-200 @error('password') border-rose-300 bg-rose-50/50 focus:border-rose-400 focus:ring-rose-100 @enderror"
                               placeholder="Masukkan password" required>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                               class="h-4 w-4 rounded-md border-gray-300 text-maroon-600 focus:ring-maroon-500 focus:ring-offset-0 cursor-pointer transition-all duration-200">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="relative inline-flex items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-maroon-700 to-maroon-800 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-maroon-200 hover:shadow-xl hover:shadow-maroon-300 hover:from-maroon-800 hover:to-maroon-900 active:scale-[0.98] transition-all duration-200 w-full overflow-hidden group">
                    <span class="absolute inset-0 bg-white/10 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <svg class="h-5 w-5 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span class="relative">Masuk ke Dashboard</span>
                </button>
            </form>

            <div class="mt-6 rounded-xl bg-gradient-to-br from-cream-50 via-white to-maroon-50 p-4 text-xs text-gray-500 ring-1 ring-maroon-100/60 shadow-sm">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="h-4 w-4 text-maroon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold text-gray-700 text-xs">Akun Demo</span>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between rounded-lg bg-maroon-50/80 px-3 py-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-maroon-100">
                                <svg class="h-3 w-3 text-maroon-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="font-mono text-maroon-700 font-medium text-xs truncate">admin@restoran.test</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="font-mono text-gray-400 text-[11px]">password</span>
                            <span class="inline-flex items-center rounded-md bg-maroon-100 px-2 py-0.5 text-[10px] font-semibold text-maroon-700">Admin</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-emerald-50/80 px-3 py-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-emerald-100">
                                <svg class="h-3 w-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="font-mono text-emerald-700 font-medium text-xs truncate">kasir@restoran.test</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="font-mono text-gray-400 text-[11px]">password</span>
                            <span class="inline-flex items-center rounded-md bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">Kasir</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-cream-200/40 text-xs mt-6"
           x-show="loaded"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:enter-duration.500ms
           x-transition:enter-delay.400ms>
            &copy; {{ date('Y') }} Restoran KasirKu. All rights reserved.
        </p>
    </div>

</body>
</html>
