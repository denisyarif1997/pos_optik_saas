<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? "Dashboard" }} - {{ app("activeStore")->get()?->name ?? "Optik" }}</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
    @livewireStyles
</head>
<body class="min-h-screen antialiased bg-canvas text-text-main"
      x-data="{
          mobileOpen: false,
          desktopCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
          toggleDesktop() {
              this.desktopCollapsed = !this.desktopCollapsed;
              localStorage.setItem('sidebar_collapsed', this.desktopCollapsed);
          }
      }"
      x-init="$watch('mobileOpen', value => document.body.classList.toggle('overflow-hidden', value))"
      @resize.window="if (window.innerWidth >= 1024) mobileOpen = false">

@php
$userRole = app('activeStore')->role();
$isAllowed = function ($item) use ($userRole) {
    if (empty($item['roles'])) {
        return true;
    }
    if ($userRole === 'owner' || $userRole === null) {
        return true;
    }
    return in_array($userRole, $item['roles'], true);
};

$menuGroups = [
    [
        'title' => 'Utama',
        'items' => [
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
        ],
    ],
    [
        'title' => 'Transaksi',
        'items' => [
            ['route' => 'pos.cashier', 'active' => 'pos.*', 'label' => 'Kasir POS', 'icon' => 'credit-card', 'roles' => ['owner', 'kasir']],
            ['route' => 'sales.index', 'active' => 'sales.*', 'label' => 'Riwayat Transaksi', 'icon' => 'wallet'],
            // ['route' => 'optical-orders.index', 'active' => 'optical-orders.*', 'label' => 'Pesanan Kacamata', 'icon' => 'glasses'],
            ['route' => 'optical-orders.index', 'active' => ['optical-orders.index', 'optical-orders.create', 'optical-orders.edit', 'optical-orders.show'], 'label' => 'Pesanan Kacamata', 'icon' => 'glasses'],
            ['route' => 'optical-orders.receivables', 'active' => 'optical-orders.receivables', 'label' => 'Piutang Pesanan', 'icon' => 'wallet', 'roles' => ['owner', 'kasir', 'optician']],
            ['route' => 'returns.index', 'active' => 'returns.*', 'label' => 'Retur Penjualan', 'icon' => 'return', 'roles' => ['owner', 'kasir']],
        ],
    ],
    [
        'title' => 'Pelanggan',
        'items' => [
            ['route' => 'customers.index', 'active' => 'customers.*', 'label' => 'Daftar Pelanggan', 'icon' => 'users'],
            ['route' => 'customers.create', 'active' => 'customers.create', 'label' => 'Tambah Pelanggan', 'icon' => 'plus'],
            ['route' => 'warranties.index', 'active' => 'warranties.*', 'label' => 'Klaim Garansi', 'icon' => 'shield'],
        ],
    ],
    [
        'title' => 'Operasional',
        'items' => [
            ['route' => 'products.index', 'active' => 'products.*', 'label' => 'Produk & Lensa', 'icon' => 'box', 'roles' => ['owner', 'stok']],
            ['route' => 'categories.index', 'active' => 'categories.*', 'label' => 'Kategori', 'icon' => 'tag', 'roles' => ['owner', 'stok']],
            ['route' => 'inventory.index', 'active' => 'inventory.*', 'label' => 'Stok Lensa', 'icon' => 'layers', 'roles' => ['owner', 'stok']],
            ['route' => 'stock-opnames.index', 'active' => 'stock-opnames.*', 'label' => 'Stock Opname', 'icon' => 'check', 'roles' => ['owner', 'stok']],
            ['route' => 'suppliers.index', 'active' => 'suppliers.*', 'label' => 'Supplier', 'icon' => 'truck', 'roles' => ['owner', 'stok']],
            ['route' => 'purchases.index', 'active' => 'purchases.*', 'label' => 'Pembelian', 'icon' => 'truck', 'roles' => ['owner', 'stok']],
            ['route' => 'expenses.index', 'active' => 'expenses.*', 'label' => 'Biaya Pengeluaran', 'icon' => 'wallet', 'roles' => ['owner']],
            ['route' => 'lab.tracker', 'active' => 'lab.*', 'label' => 'Servis Lab', 'icon' => 'flask', 'roles' => ['owner', 'optician']],
        ],
    ],
    [
        'title' => 'Laporan',
        'items' => [
            ['route' => 'reports.index', 'active' => 'reports.*', 'label' => 'Laporan Penjualan', 'icon' => 'chart', 'roles' => ['owner']],
        ],
    ],
    [
        'title' => 'Pengelolaan',
        'items' => [
            ['route' => 'settings.store', 'active' => 'settings.store*', 'label' => 'Profil Toko', 'icon' => 'store', 'roles' => ['owner']],
            ['route' => 'settings.staff', 'active' => 'settings.staff*', 'label' => 'Staf & Hak Akses', 'icon' => 'users', 'roles' => ['owner']],
            ['route' => 'billing.index', 'active' => 'billing.*', 'label' => 'Paket & Langganan', 'icon' => 'credit-card', 'roles' => ['owner']],
        ],
    ],
];
@endphp

    <div class="min-h-screen flex flex-col lg:flex-row w-full relative">

        <!-- ========================================== -->
        <!-- 1. DESKTOP SIDEBAR (Visible on lg: >= 1024px) -->
        <!-- ========================================== -->
        <aside :class="desktopCollapsed ? 'w-20' : 'w-64'"
               class="hidden lg:flex flex-col sticky top-0 h-screen bg-surface border-r border-line z-30 transition-all duration-300 flex-shrink-0 select-none">

            <!-- Desktop Sidebar Header -->
            <div class="px-4 py-4 border-b border-line flex items-center justify-between gap-2.5 min-h-[68px]">
                <div class="flex items-center gap-3 min-w-0 flex-1 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-emerald text-white flex items-center justify-center shadow-sm shadow-emerald/20 flex-shrink-0">
                        <x-icon name="glasses" class="w-4 h-4" />
                    </div>
                    <div x-show="!desktopCollapsed"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-x-2"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="min-w-0 flex-1">
                        <div class="text-sm font-semibold truncate text-text-main leading-tight">{{ app('activeStore')->get()?->name ?? 'Optik' }}</div>
                        <div class="text-[10px] text-text-secondary truncate mt-0.5">Kasir &amp; manajemen optik</div>
                    </div>
                </div>
                <button @click="toggleDesktop()"
                        type="button"
                        :title="desktopCollapsed ? 'Perluas Sidebar' : 'Ciutkan Sidebar'"
                        class="p-1.5 rounded-lg text-text-secondary hover:text-emerald hover:bg-emerald-bg transition flex-shrink-0">
                    <x-icon name="sidebar" class="w-4 h-4" />
                </button>
            </div>

            <!-- Desktop User Profile Badge -->
            <div class="px-4 py-2.5 border-b border-line-soft bg-canvas/60">
                <div class="flex items-center gap-2.5" :class="{ 'justify-center': desktopCollapsed }">
                    <div class="w-7 h-7 rounded-full bg-emerald text-white flex items-center justify-center text-[10px] font-bold shadow-xs flex-shrink-0"
                         :title="desktopCollapsed ? '{{ auth()->user()?->name }} ({{ app('activeStore')->role() ?? '-' }})' : ''">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div x-show="!desktopCollapsed"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="min-w-0 flex-1 overflow-hidden">
                        <div class="text-xs font-semibold text-text-main truncate leading-tight">{{ auth()->user()?->name ?? 'User' }}</div>
                        <div class="text-[10px] text-emerald-dark font-medium capitalize mt-0.5">{{ app('activeStore')->role() ?? 'Staff' }}</div>
                    </div>
                </div>
            </div>

            <!-- Desktop Navigation Links -->
            <nav class="flex-1 px-3 py-3 overflow-y-auto sidebar-scroll space-y-0.5">
                @foreach ($menuGroups as $group)
                    @php
                        $filteredItems = array_filter($group['items'], $isAllowed);
                    @endphp
                    @if (count($filteredItems) > 0)
                        <!-- Group Header -->
                        <div class="px-3 pt-3.5 pb-1 transition-all duration-200" :class="{ 'hidden': desktopCollapsed }">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-text-muted">{{ $group['title'] }}</span>
                        </div>
                        <div class="my-2 border-t border-line-soft transition-all duration-200" :class="{ 'hidden': !desktopCollapsed }"></div>

                        @foreach ($filteredItems as $item)
                            @php $isActive = request()->routeIs($item['active']); @endphp
                            <a href="{{ route($item['route']) }}"
                               :title="desktopCollapsed ? '{{ $item['label'] }}' : ''"
                               :class="desktopCollapsed ? 'justify-center px-2 py-2.5' : 'px-3 py-2'"
                               class="navlink text-xs font-medium rounded-xl flex items-center gap-3 transition-colors {{ $isActive ? 'navlink-active' : '' }}">
                                <x-icon :name="$item['icon']" class="w-4 h-4 flex-shrink-0" />
                                <span x-show="!desktopCollapsed" class="truncate transition-opacity duration-200">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    @endif
                @endforeach
            </nav>

            <!-- Desktop Sidebar Footer (Logout) -->
            <div class="p-3 border-t border-line mt-auto bg-surface">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            :title="desktopCollapsed ? 'Keluar' : ''"
                            :class="desktopCollapsed ? 'justify-center px-2 py-2.5' : 'px-3 py-2.5'"
                            class="w-full flex items-center gap-2.5 text-xs font-medium text-text-secondary hover:text-error hover:bg-error/5 rounded-xl transition-colors">
                        <x-icon name="logout" class="w-4 h-4 flex-shrink-0" />
                        <span x-show="!desktopCollapsed" class="truncate">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- 2. PORTRAIT & MOBILE DRAWER (Screens < lg) -->
        <!-- ========================================== -->

        <!-- Mobile Drawer Overlay Backdrop -->
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false"
             class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden">
        </div>

        <!-- Mobile Drawer Panel -->
        <aside x-show="mobileOpen"
               x-cloak
               x-transition:enter="transition-transform ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform ease-in duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               @keydown.window.escape="mobileOpen = false"
               class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] flex flex-col bg-surface border-r border-line shadow-2xl lg:hidden select-none">

            <!-- Mobile Drawer Header -->
            <div class="px-5 py-4 border-b border-line flex items-center justify-between gap-3 min-h-[68px]">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-9 h-9 rounded-xl bg-emerald text-white flex items-center justify-center shadow-sm shadow-emerald/20 flex-shrink-0">
                        <x-icon name="glasses" class="w-4 h-4" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-semibold truncate text-text-main leading-tight">{{ app('activeStore')->get()?->name ?? 'Optik' }}</div>
                        <div class="text-[10px] text-text-secondary truncate mt-0.5">Kasir &amp; manajemen optik</div>
                    </div>
                </div>
                <button @click="mobileOpen = false"
                        type="button"
                        aria-label="Tutup Menu"
                        class="p-2 rounded-xl text-text-secondary hover:text-text-main hover:bg-canvas transition flex-shrink-0">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <!-- Mobile Drawer User Profile Badge -->
            <div class="px-5 py-3 border-b border-line-soft bg-canvas/60 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-emerald text-white flex items-center justify-center text-xs font-bold shadow-xs flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-semibold text-text-main truncate leading-tight">{{ auth()->user()?->name ?? 'User' }}</div>
                    <div class="text-[10px] text-emerald-dark font-medium capitalize mt-0.5">{{ app('activeStore')->role() ?? 'Staff' }}</div>
                </div>
            </div>

            <!-- Mobile Drawer Nav Links -->
            <nav class="flex-1 px-3 py-3 overflow-y-auto sidebar-scroll space-y-0.5">
                @foreach ($menuGroups as $group)
                    @php
                        $filteredItems = array_filter($group['items'], $isAllowed);
                    @endphp
                    @if (count($filteredItems) > 0)
                        <div class="px-3 pt-3.5 pb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-text-muted">{{ $group['title'] }}</span>
                        </div>
                        @foreach ($filteredItems as $item)
                            @php $isActive = request()->routeIs($item['active']); @endphp
                            <a href="{{ route($item['route']) }}"
                               @click="mobileOpen = false"
                               class="navlink px-3 py-2 text-xs font-medium rounded-xl flex items-center gap-3 transition-colors {{ $isActive ? 'navlink-active' : '' }}">
                                <x-icon :name="$item['icon']" class="w-4 h-4 flex-shrink-0" />
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    @endif
                @endforeach
            </nav>

            <!-- Mobile Drawer Footer (Logout) -->
            <div class="p-4 border-t border-line mt-auto bg-surface">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            @click="mobileOpen = false"
                            class="w-full flex items-center gap-2.5 px-3 py-2.5 text-xs font-medium text-text-secondary hover:text-error hover:bg-error/5 rounded-xl transition-colors">
                        <x-icon name="logout" class="w-4 h-4 flex-shrink-0" />
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- 3. MAIN PAGE CONTENT WRAPPER -->
        <!-- ========================================== -->
        <div class="flex-1 min-w-0 flex flex-col min-h-screen">

            <!-- Top Header -->
            <header class="sticky top-0 z-20 bg-surface/90 backdrop-blur border-b border-line">
                <div class="px-4 lg:px-6 py-3 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Mobile/Portrait Drawer Toggle (< 1024px) -->
                        <button @click="mobileOpen = true"
                                type="button"
                                class="lg:hidden p-2 -ml-2 text-text-main hover:text-emerald rounded-xl hover:bg-emerald-bg transition"
                                aria-label="Buka Menu Navigasi">
                            <x-icon name="menu" class="w-5 h-5" />
                        </button>

                        <!-- Desktop Sidebar Collapse Toggle (>= 1024px) -->
                        {{-- <button @click="toggleDesktop()"
                                type="button"
                                class="hidden lg:flex p-2 -ml-2 text-text-main hover:text-emerald rounded-xl hover:bg-emerald-bg transition"
                                :title="desktopCollapsed ? 'Perluas Sidebar' : 'Ciutkan Sidebar'"
                                aria-label="Toggle Sidebar">
                            <x-icon name="sidebar" class="w-5 h-5" />
                        </button> --}}

                        <div class="min-w-0">
                            <h1 class="text-sm font-semibold text-text-main leading-tight truncate">{{ $title ?? "Dashboard" }}</h1>
                            <div class="text-[11px] text-text-secondary truncate">{{ now()->translatedFormat("l, d F Y") }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 flex-shrink-0">
                        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-bg text-[11px] font-semibold text-emerald-dark border border-emerald-soft">
                            <x-icon name="store" class="w-3.5 h-3.5" />
                            <span class="truncate max-w-[140px]">{{ app("activeStore")->get()?->name ?? "-" }}</span>
                        </span>
                        <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-canvas border border-line">
                            <div class="w-6 h-6 rounded-full bg-emerald text-white flex items-center justify-center text-[10px] font-bold shadow-xs">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline text-xs font-medium text-text-main truncate max-w-[120px]">{{ auth()->user()?->name ?? "User" }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Page Slot -->
            <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
                @if (session("error"))
                    <div class="mb-4">
                        <x-alert type="error" :message="session('error')" />
                    </div>
                @endif
                @if (session("success"))
                    <div class="mb-4">
                        <x-alert type="success" :message="session('success')" />
                    </div>
                @endif
                {{ $slot }}
            </main>

        </div>

    </div>

    @livewireScripts
</body>
</html>
