<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? "Dashboard" }} - {{ app("activeStore")->get()?->name ?? "Optik" }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(["resources/css/app.css", "resources/js/app.js"])
    @livewireStyles
    <style>
        body { font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen antialiased bg-canvas text-text-main"
      x-data="{
          mobileOpen: false,
          openMenu: null,
          toggle(key) { this.openMenu = this.openMenu === key ? null : key },
      }"
      x-init="$watch('mobileOpen', v => document.body.classList.toggle('overflow-hidden', v))"
      @resize.window="if (window.innerWidth >= 1024) mobileOpen = false"
      @keydown.escape.window="mobileOpen = false; openMenu = null">

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

// 'nav' = teks di bar atas (dipakai bila grup hanya punya 1 menu -> jadi link langsung)
$menuGroups = [
    [
        'title' => 'Utama',
        'nav' => 'Dashboard',
        'items' => [
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
        ],
    ],
    [
        'title' => 'Transaksi',
        'items' => [
            ['route' => 'pos.cashier', 'active' => 'pos.*', 'label' => 'Kasir POS', 'icon' => 'credit-card', 'roles' => ['owner', 'kasir']],
            ['route' => 'sales.index', 'active' => 'sales.*', 'label' => 'Riwayat Transaksi', 'icon' => 'wallet'],
            ['route' => 'optical-orders.index', 'active' => ['optical-orders.index', 'optical-orders.create', 'optical-orders.edit', 'optical-orders.show'], 'label' => 'Pesanan Kacamata', 'icon' => 'glasses'],
            ['route' => 'optical-orders.receivables', 'active' => 'optical-orders.receivables', 'label' => 'Piutang Pesanan', 'icon' => 'wallet', 'roles' => ['owner', 'kasir', 'optician']],
            ['route' => 'returns.index', 'active' => 'returns.*', 'label' => 'Retur Penjualan', 'icon' => 'return', 'roles' => ['owner', 'kasir']],
        ],
    ],
    [
        'title' => 'Pelanggan',
        'items' => [
            ['route' => 'customers.index', 'active' => 'customers.*', 'label' => 'Daftar Pelanggan', 'icon' => 'users'],
            // ['route' => 'customers.create', 'active' => 'customers.create', 'label' => 'Tambah Pelanggan', 'icon' => 'plus'],
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
            // ['route' => 'suppliers.index', 'active' => 'suppliers.*', 'label' => 'Supplier', 'icon' => 'truck', 'roles' => ['owner', 'stok']],
            // ['route' => 'purchases.index', 'active' => 'purchases.*', 'label' => 'Pembelian', 'icon' => 'truck', 'roles' => ['owner', 'stok']],
            ['route' => 'expenses.index', 'active' => 'expenses.*', 'label' => 'Biaya Pengeluaran', 'icon' => 'wallet', 'roles' => ['owner']],
            ['route' => 'cash-ins.index', 'active' => 'cash-ins.*', 'label' => 'Kas Masuk', 'icon' => 'plus-circle', 'roles' => ['owner']],
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
            ['route' => 'cash-accounts.index', 'active' => 'cash-accounts.*', 'label' => 'Master Kas', 'icon' => 'wallet', 'roles' => ['owner']],
            ['route' => 'billing.index', 'active' => 'billing.*', 'label' => 'Paket & Langganan', 'icon' => 'credit-card', 'roles' => ['owner']],
        ],
    ],
];

// Saring menu sesuai role, tandai grup yang sedang aktif
$groups = collect($menuGroups)
    ->map(function ($g) use ($isAllowed) {
        $g['items'] = array_values(array_filter($g['items'], $isAllowed));
        $g['isActive'] = collect($g['items'])->contains(fn ($i) => request()->routeIs($i['active']));
        $g['navLabel'] = $g['nav'] ?? $g['title'];
        return $g;
    })
    ->filter(fn ($g) => count($g['items']) > 0)
    ->values();

$storeName = app('activeStore')->get()?->name ?? 'Optik';
$userName  = auth()->user()?->name ?? 'User';
$userInit  = strtoupper(substr($userName, 0, 1));
$roleLabel = app('activeStore')->role() ?? 'Staff';
@endphp

    <!-- ================= TOP BAR ================= -->
    <header class="sticky top-0 z-40 bg-surface border-b border-line">
        <div class="mx-auto max-w-[1600px] h-14 px-4 lg:px-8 flex items-center gap-4">

            <!-- Hamburger (mobile) -->
            <button @click="mobileOpen = true"
                    type="button"
                    class="lg:hidden -ml-2 p-2 rounded-md text-text-secondary hover:text-text-main hover:bg-canvas"
                    aria-label="Buka menu">
                <x-icon name="menu" class="w-5 h-5" />
            </button>

            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0 flex-shrink-0">
                <span class="w-8 h-8 rounded-md bg-emerald text-white flex items-center justify-center flex-shrink-0">
                    <x-icon name="glasses" class="w-[18px] h-[18px]" />
                </span>
                <span class="hidden sm:block text-sm font-semibold text-text-main truncate max-w-[160px]">{{ $storeName }}</span>
            </a>

            <!-- Navigasi desktop -->
            <nav class="hidden lg:flex items-stretch h-14 ml-4 gap-1" aria-label="Menu utama" @click.outside="openMenu = null">
                @foreach ($groups as $group)
                    @php $key = 'g' . $loop->index; @endphp

                    @if (count($group['items']) === 1)
                        {{-- Grup 1 menu: link langsung --}}
                        @php $only = $group['items'][0]; @endphp
                        <a href="{{ route($only['route']) }}"
                           @if ($group['isActive']) aria-current="page" @endif
                           class="flex items-center px-3 -mb-px text-sm font-medium border-b-2 transition-colors
                                  {{ $group['isActive']
                                      ? 'text-text-main border-emerald'
                                      : 'text-text-secondary border-transparent hover:text-text-main' }}">
                            {{ $group['navLabel'] }}
                        </a>
                    @else
                        {{-- Grup banyak menu: dropdown --}}
                        <div class="relative flex">
                            <button type="button"
                                    @click="toggle('{{ $key }}')"
                                    :aria-expanded="openMenu === '{{ $key }}'"
                                    class="flex items-center gap-1 px-3 -mb-px text-sm font-medium border-b-2 transition-colors
                                           {{ $group['isActive']
                                               ? 'text-text-main border-emerald'
                                               : 'text-text-secondary border-transparent hover:text-text-main' }}"
                                    :class="openMenu === '{{ $key }}' ? '!text-text-main' : ''">
                                {{ $group['navLabel'] }}
                                <svg class="w-3.5 h-3.5 text-text-muted transition-transform duration-150"
                                     :class="openMenu === '{{ $key }}' ? 'rotate-180' : ''"
                                     viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 7.5l5 5 5-5" />
                                </svg>
                            </button>

                            <div x-show="openMenu === '{{ $key }}'"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute left-0 top-full mt-1 w-60 p-1.5 bg-surface border border-line rounded-lg shadow-lg">
                                @foreach ($group['items'] as $item)
                                    @php $isActive = request()->routeIs($item['active']); @endphp
                                    <a href="{{ route($item['route']) }}"
                                       @if ($isActive) aria-current="page" @endif
                                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-md text-sm transition-colors
                                              {{ $isActive
                                                  ? 'bg-emerald-bg text-emerald-dark font-medium'
                                                  : 'text-text-main hover:bg-canvas' }}">
                                        <x-icon :name="$item['icon']"
                                                class="w-4 h-4 flex-shrink-0 {{ $isActive ? '' : 'text-text-muted' }}" />
                                        <span class="truncate">{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>

            <!-- Menu user -->
            <div class="relative ml-auto" @click.outside="openMenu === 'user' && (openMenu = null)">
                <button type="button"
                        @click="toggle('user')"
                        class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full hover:bg-canvas transition-colors"
                        aria-label="Menu akun">
                    <span class="w-8 h-8 rounded-full bg-canvas border border-line text-text-main flex items-center justify-center text-xs font-semibold">
                        {{ $userInit }}
                    </span>
                    <span class="hidden md:block text-sm font-medium text-text-main truncate max-w-[140px]">{{ $userName }}</span>
                    <svg class="hidden md:block w-3.5 h-3.5 text-text-muted" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 7.5l5 5 5-5" />
                    </svg>
                </button>

                <div x-show="openMenu === 'user'"
                     x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute right-0 top-full mt-1 w-56 bg-surface border border-line rounded-lg shadow-lg overflow-hidden">
                    <div class="px-3.5 py-3 border-b border-line-soft">
                        <div class="text-sm font-medium text-text-main truncate">{{ $userName }}</div>
                        <div class="text-xs text-text-secondary capitalize">{{ $roleLabel }} &middot; {{ $storeName }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="p-1.5">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-md text-sm text-text-main hover:bg-error/5 hover:text-error transition-colors">
                            <x-icon name="logout" class="w-4 h-4 flex-shrink-0" />
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= DRAWER MOBILE ================= -->
    <div x-show="mobileOpen" x-cloak
         x-transition.opacity.duration.200ms
         @click="mobileOpen = false"
         class="fixed inset-0 z-50 bg-black/40 lg:hidden"></div>

    <aside x-show="mobileOpen" x-cloak
           x-transition:enter="transition-transform ease-out duration-200"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition-transform ease-in duration-150"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 z-[60] w-80 max-w-[85vw] flex flex-col bg-surface shadow-2xl lg:hidden">

        <div class="h-14 px-4 flex items-center justify-between border-b border-line flex-shrink-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="w-8 h-8 rounded-md bg-emerald text-white flex items-center justify-center flex-shrink-0">
                    <x-icon name="glasses" class="w-[18px] h-[18px]" />
                </span>
                <span class="text-sm font-semibold truncate">{{ $storeName }}</span>
            </div>
            <button @click="mobileOpen = false" type="button" aria-label="Tutup menu"
                    class="p-2 -mr-2 rounded-md text-text-secondary hover:text-text-main hover:bg-canvas">
                <x-icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5" aria-label="Menu utama">
            @foreach ($groups as $group)
                <div>
                    @if (count($group['items']) > 1)
                        <div class="px-2.5 pb-1 text-xs font-medium text-text-muted">{{ $group['title'] }}</div>
                    @endif
                    <div class="space-y-px">
                        @foreach ($group['items'] as $item)
                            @php $isActive = request()->routeIs($item['active']); @endphp
                            <a href="{{ route($item['route']) }}"
                               @click="mobileOpen = false"
                               @if ($isActive) aria-current="page" @endif
                               class="flex items-center gap-3 px-2.5 h-10 rounded-md text-sm transition-colors
                                      {{ $isActive
                                          ? 'bg-emerald-bg text-emerald-dark font-medium'
                                          : 'text-text-main hover:bg-canvas' }}">
                                <x-icon :name="$item['icon']" class="w-[18px] h-[18px] flex-shrink-0 {{ $isActive ? '' : 'text-text-muted' }}" />
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="p-3 border-t border-line flex items-center gap-3 flex-shrink-0">
            <span class="w-9 h-9 rounded-full bg-canvas border border-line flex items-center justify-center text-xs font-semibold flex-shrink-0">{{ $userInit }}</span>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-medium truncate leading-tight">{{ $userName }}</div>
                <div class="text-xs text-text-secondary capitalize">{{ $roleLabel }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" aria-label="Keluar"
                        class="p-2 rounded-md text-text-secondary hover:text-error hover:bg-error/5 transition-colors">
                    <x-icon name="logout" class="w-5 h-5" />
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= KONTEN ================= -->
    <main class="mx-auto w-full max-w-[1600px] px-4 lg:px-8 py-6">

        <div class="mb-6 flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1">
            <h1 class="text-xl font-semibold tracking-tight text-text-main">{{ $title ?? "Dashboard" }}</h1>
            <div class="text-sm text-text-secondary tabular-nums">{{ now()->translatedFormat("l, d F Y") }}</div>
        </div>

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

    <x-notification />

    @livewireScripts
</body>
</html>