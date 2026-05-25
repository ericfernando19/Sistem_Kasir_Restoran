<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Restoran Kasir') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-cream-50">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
    <div x-show="sidebarOpen" @@click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden print:hidden" x-cloak></div>

    <aside class="fixed inset-y-0 left-0 z-30 flex w-64 shrink-0 flex-col bg-gradient-to-b from-maroon-800 via-maroon-800 to-maroon-900 shadow-2xl transition-transform lg:static lg:translate-x-0 print:hidden"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <div class="flex h-16 items-center justify-between gap-2 border-b border-white/10 px-5">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-cream-300 to-cream-500 shadow-lg shadow-black/20">
                    <svg class="h-5 w-5 text-maroon-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-base font-bold text-white tracking-tight">Kasir<span class="text-cream-400">Ku</span></span>
            </div>
            <button @@click="sidebarOpen = false" class="text-white/60 hover:text-white lg:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-0.5">
            <x-nav-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="chart-bar">
                Dashboard
            </x-nav-item>

            @if(auth()->user()->isAdmin())
            <div class="flex items-center gap-3 px-2 pt-5 pb-1">
                <span class="h-px flex-1 bg-white/10"></span>
                <span class="text-[10px] font-semibold uppercase tracking-wider text-white/40">Master Data</span>
                <span class="h-px flex-1 bg-white/10"></span>
            </div>
            <x-nav-item href="{{ route('menus.index') }}" :active="request()->routeIs('menus.*')" icon="cube">
                Menu Restoran
            </x-nav-item>
            <x-nav-item href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')" icon="folder">
                Kategori Menu
            </x-nav-item>
            <x-nav-item href="{{ route('tables.index') }}" :active="request()->routeIs('tables.*')" icon="table">
                Manajemen Meja
            </x-nav-item>
            @endif

            <div class="flex items-center gap-3 px-2 pt-5 pb-1">
                <span class="h-px flex-1 bg-white/10"></span>
                <span class="text-[10px] font-semibold uppercase tracking-wider text-white/40">Transaksi</span>
                <span class="h-px flex-1 bg-white/10"></span>
            </div>
            <x-nav-item href="{{ route('orders.create') }}" :active="request()->routeIs('orders.create')" icon="shopping-cart">
                POS Kasir
            </x-nav-item>
            <x-nav-item href="{{ route('orders.index') }}" :active="request()->routeIs('orders.index')" icon="clipboard-list">
                Pesanan Aktif
            </x-nav-item>
            <x-nav-item href="{{ route('orders.history') }}" :active="request()->routeIs('orders.history') || request()->routeIs('orders.show')" icon="history">
                Riwayat Pesanan
            </x-nav-item>

            @if(auth()->user()->isAdmin())
            <div class="flex items-center gap-3 px-2 pt-5 pb-1">
                <span class="h-px flex-1 bg-white/10"></span>
                <span class="text-[10px] font-semibold uppercase tracking-wider text-white/40">Laporan</span>
                <span class="h-px flex-1 bg-white/10"></span>
            </div>
            <x-nav-item href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="chart-pie">
                Laporan
            </x-nav-item>
            <x-nav-item href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="users">
                Pengguna
            </x-nav-item>
            @endif
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3 mb-2 px-2">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cream-300 to-cream-500 text-sm font-bold text-maroon-800 shadow-md shadow-black/10">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white/90 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-white/50 capitalize leading-tight">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-white/50 hover:bg-white/10 hover:text-white transition-all duration-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="flex flex-1 flex-col overflow-hidden">
        <header class="flex h-16 shrink-0 items-center gap-4 border-b border-cream-200 bg-white/90 backdrop-blur-xl px-4 lg:px-6 shadow-sm print:hidden">
            <button @@click="sidebarOpen = true" class="-ml-1 flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-cream-100 hover:text-gray-600 lg:hidden transition-all duration-200">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-maroon-500 to-maroon-700 text-xs font-bold text-white shadow-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-gray-900 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-gray-500 capitalize leading-tight">{{ auth()->user()->role }}</p>
                </div>
            </div>

            <div class="flex-1"></div>

            <div class="flex items-center gap-2">
                <div class="flex h-5 w-px bg-cream-200"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-400 hover:bg-cream-100 hover:text-gray-600 transition-all duration-200" title="Keluar">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            <div class="max-w-7xl mx-auto">
                @if (session('success'))
                <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 px-5 py-4 text-sm text-emerald-800 flex items-center gap-3 shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-cloak>
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @@click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
                </div>
                @endif

                @if (session('error'))
                <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-200/80 px-5 py-4 text-sm text-rose-800 flex items-center gap-3 shadow-sm" x-data="{ show: true }" x-show="show" x-cloak>
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-rose-100">
                        <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button @@click="show = false" class="text-rose-400 hover:text-rose-600">&times;</button>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
