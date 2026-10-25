<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? "Dashboard" }} - {{ app("activeStore")->get()?->name ?? "Optik" }}</title>
@vite(["resources/css/app.css", "resources/js/app.js"])
@livewireStyles
</head>
<body class="min-h-screen antialiased bg-canvas text-text-main" x-data="{ drawerOpen: false }">

<!-- Mobile Drawer Overlay -->
<div x-show="drawerOpen"
     x-transition:enter="transition-opacity duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="drawerOpen = false"
     class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden"></div>

<!-- Mobile Drawer Panel -->
<aside x-show="drawerOpen"
       x-transition:enter="transition-transform duration-300 ease-out"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition-transform duration-200 ease-in"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="fixed inset-y-0 left-0 z-50 w-full lg:w-[260px] bg-emerald-soft border-l border-line shadow-2xl lg:shadow-none lg:static lg:inset-y-0 lg:left-0 lg:flex lg:flex-col min-h-screen"
       @click.away="drawerOpen = false">

  <!-- Drawer Header -->
  <div class="px-5 pt-6 pb-4 border-b border-line">
    <div class="flex items-center gap-2.5">
      <div class="w-9 h-9 rounded-xl bg-emerald text-white flex items-center justify-center shadow-sm shadow-emerald/20">
        <x-icon name="glasses" class="w-4 h-4" />
      </div>
      <div class="min-w-0 flex-1">
        <div class="text-sm font-semibold truncate text-text-main">{{ app("activeStore")->get()?->name ?? "Optik" }}</div>
        <div class="text-[10px] text-text-secondary">Kasir &amp; manajemen optik</div>
      </div>
    </div>
    <div class="mt-3 text-[10px] text-text-secondary">


      {{ auth()->user()?->name }} &middot; <span class="capitalize">{{ app("activeStore")->role() ?? "-" }}</span>
    </div>
  </div>

  <nav class="flex-1 p-2 overflow-y-auto">
    <!-- MAIN -->
    <a href="{{ route("dashboard") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("dashboard") ? "navlink-active" : "" }}"><x-icon name="dashboard" class="w-4 h-4" /> Dashboard</a>

    <!-- TRANSAKSI -->
    <div class="px-3 pt-5 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Transaksi</div>
    <a href="{{ route("pos.cashier") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("pos.cashier") ? "navlink-active" : "" }}"><x-icon name="credit-card" class="w-4 h-4" /> Kasir</a>
    <a href="{{ route("sales.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("sales.index") ? "navlink-active" : "" }}"><x-icon name="wallet" class="w-4 h-4" /> Riwayat Transaksi</a>
    <a href="{{ route("optical-orders.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("optical-orders.*") ? "navlink-active" : "" }}"><x-icon name="glasses" class="w-4 h-4" /> Pesanan Kacamata</a>

    <!-- PELANGGAN -->
    <div class="px-3 pt-5 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Pelanggan</div>
    <a href="{{ route("customers.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("customers.*") ? "navlink-active" : "" }}"><x-icon name="users" class="w-4 h-4" /> Daftar Pelanggan</a>
    <a href="{{ route("customers.create") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("customers.create") ? "navlink-active" : "" }}"><x-icon name="plus" class="w-4 h-4" /> Tambah Pelanggan</a>

    <!-- OPERASIONAL -->
    <div class="px-3 pt-5 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Operasional</div>
    <a href="{{ route("products.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("products.*") ? "navlink-active" : "" }}"><x-icon name="box" class="w-4 h-4" /> Produk &amp; Kategori</a>
    <a href="{{ route("categories.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("categories.*") ? "active" : "" }}"><x-icon name="tag" class="w-4 h-4" /> Kategori</a>
    <a href="{{ route("inventory.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("inventory.*") ? "active" : "" }}"><x-icon name="layers" class="w-4 h-4" /> Stok Barang</a>
    <a href="{{ route("stock-opnames.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("stock-opnames.*") ? "active" : "" }}"><x-icon name="layers" class="w-4 h-4" /> Stock Opname</a>
    <a href="{{ route("suppliers.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("suppliers.*") ? "active" : "" }}"><x-icon name="users" class="w-4 h-4" /> Supplier</a>
    <a href="{{ route("purchases.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("purchases.*") ? "active" : "" }}"><x-icon name="truck" class="w-4 h-4" /> Pembelian</a>
    <a href="{{ route("lab.tracker") }}" class="navlink {{ request()->routeIs("lab.tracker") ? "active" : "" }}"><x-icon name="flask" class="w-4 h-4" /> Servis Lab</a>

    <!-- LAPORAN -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Laporan</div>
    <a href="{{ route("reports.index") }}" class="navlink {{ request()->routeIs("reports.*") ? "active" : "" }}"><x-icon name="chart" class="w-4 h-4" /> Laporan Penjualan</a>

    <!-- PENGELOLAAN -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Pengelolaan</div>
    <a href="{{ route("settings.store") }}" class="navlink {{ request()->routeIs("settings.store") ? "active" : "" }}"><x-icon name="store" class="w-4 h-4" /> Profil Toko</a>
    <a href="{{ route("settings.staff") }}" class="navlink {{ request()->routeIs("settings.staff") ? "active" : "" }}"><x-icon name="users" class="w-4 h-4" /> Staf & Hak Akses</a>
    <a href="{{ route("billing.index") }}" class="navlink {{ request()->routeIs("billing.*") ? "active" : "" }}"><x-icon name="wallet" class="w-4 h-4" /> Paket & Langganan</a>
  </nav>

  <div class="px-5 pb-4 border-t border-line mt-auto">
    <form action="{{ route("logout") }}" method="POST">
      @csrf
      <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-left text-sm text-text-main hover:bg-canvas rounded-lg transition"><x-icon name="logout" class="w-4 h-4" /> Keluar</button>
    </form>
  </div>
</aside>

<!-- Mobile Sidebar Drawer -->
<aside x-show="drawerOpen" x-cloak class="fixed inset-y-0 left-0 z-50 lg:hidden flex-col border-r border-line bg-emerald-soft transition-transform duration-300" :class="{ 'translate-x-0': drawerOpen, '-translate-x-full': !drawerOpen }">
  <div class="flex items-center justify-end px-5 pt-5 pb-4">
    <button @click="drawerOpen = false" class="p-2 rounded-lg hover:bg-canvas text-text-secondary"><x-icon name="x" class="w-5 h-5" /></button>
  </div>
  <nav class="flex-1 p-2 text-[12px] overflow-y-auto">
    <a href="{{ route("dashboard") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("dashboard") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="dashboard" class="w-4 h-4" /> Dashboard</a>
    <a href="{{ route("pos.cashier") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("pos.cashier") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="credit-card" class="w-4 h-4" /> Kasir</a>
    <a href="{{ route("sales.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("sales.index") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="wallet" class="w-4 h-4" /> Riwayat Transaksi</a>
    <a href="{{ route("optical-orders.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("optical-orders.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="glasses" class="w-4 h-4" /> Pesanan Kacamata</a>
    <a href="{{ route("customers.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("customers.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="users" class="w-4 h-4" /> Daftar Pelanggan</a>
    <a href="{{ route("products.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("products.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="box" class="w-4 h-4" /> Produk & Kategori</a>
    <a href="{{ route("categories.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("categories.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="tag" class="w-4 h-4" /> Kategori</a>
    <a href="{{ route("inventory.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("inventory.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="layers" class="w-4 h-4" /> Stok Barang</a>
    <a href="{{ route("stock-opnames.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("stock-opnames.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="layers" class="w-4 h-4" /> Stock Opname</a>
    <a href="{{ route("suppliers.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("suppliers.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="users" class="w-4 h-4" /> Supplier</a>
    <a href="{{ route("purchases.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("purchases.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="truck" class="w-4 h-4" /> Pembelian</a>
    <a href="{{ route("lab.tracker") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("lab.tracker") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="flask" class="w-4 h-4" /> Servis Lab</a>
    <a href="{{ route("reports.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("reports.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="chart" class="w-4 h-4" /> Laporan Penjualan</a>
    <a href="{{ route("settings.store") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("settings.store") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="store" class="w-4 h-4" /> Profil Toko</a>
    <a href="{{ route("settings.staff") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("settings.staff") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="users" class="w-4 h-4" /> Staf & Hak Akses</a>
    <a href="{{ route("billing.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("billing.*") ? "bg-sidebar-active" : "text-white/70 hover:bg-white/10" }}"><x-icon name="wallet" class="w-4 h-4" /> Paket & Langganan</a>
    <div class="mt-4 border-t border-white/10 pt-4">
      <form action="{{ route("logout") }}" method="POST">
        @csrf
        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-left text-sm text-white/70 hover:text-white rounded-lg transition"><x-icon name="logout" class="w-4 h-4" /> Keluar</button>
      </form>
    </div>
  </nav>
</aside>

<!-- Mobile Drawer Overlay -->
<div x-show="drawerOpen" x-cloak class="fixed inset-0 z-40 bg-black/40 lg:hidden" @click="drawerOpen = false"></div>

<!-- Mobile Drawer Panel -->
<aside x-show="drawerOpen" x-cloak class="fixed inset-y-0 left-0 z-[60] flex-col border-r border-line bg-surface min-h-screen w-[240px] lg:hidden transition-transform duration-300" :class="{ 'translate-x-0': drawerOpen, '-translate-x-full': !drawerOpen }">
  <div class="px-5 pt-5 pb-4 border-b border-line">
    <div class="flex items-center gap-2.5">
      <div class="w-8 h-8 rounded-lg bg-sidebar text-white flex items-center justify-center"><x-icon name="glasses" class="w-4 h-4" /></div>
      <div class="min-w-0">
        <div class="text-sm font-semibold truncate text-text-main">{{ app("activeStore")->get()?->name ?? "Optik" }}</div>
        <div class="text-[10px] text-text-secondary">Kasir & manajemen optik</div>
      </div>
    </div>
    <div class="mt-2 text-[10px] text-text-secondary">{{ auth()->user()?->name }} &middot; <span class="capitalize">{{ app("activeStore")->role() ?? "-" }}</span></div>
  </div>

  <nav class="flex-1 p-2 text-[12px] overflow-y-auto">
    <!-- MAIN -->
    <a href="{{ route("dashboard") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("dashboard") ? "active" : "" }}"><x-icon name="dashboard" class="w-4 h-4" /> Dashboard</a>

    <!-- TRANSAKSI -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Transaksi</div>
    <a href="{{ route("pos.cashier") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("pos.cashier") ? "active" : "" }}"><x-icon name="credit-card" class="w-4 h-4" /> Kasir</a>
    <a href="{{ route("sales.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("sales.index") ? "active" : "" }}"><x-icon name="wallet" class="w-4 h-4" /> Riwayat Transaksi</a>
    <a href="{{ route("optical-orders.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("optical-orders.*") ? "active" : "" }}"><x-icon name="glasses" class="w-4 h-4" /> Pesanan Kacamata</a>

    <!-- PELANGGAN -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Pelanggan</div>
    <a href="{{ route("customers.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("customers.*") ? "active" : "" }}"><x-icon name="users" class="w-4 h-4" /> Daftar Pelanggan</a>
    <a href="{{ route("customers.create") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("customers.create") ? "active" : "" }}"><x-icon name="plus" class="w-4 h-4" /> Tambah Pelanggan</a>

    <!-- OPERASIONAL -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Operasional</div>
    <a href="{{ route("products.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("products.*") ? "active" : "" }}"><x-icon name="box" class="w-4 h-4" /> Produk & Kategori</a>
    <a href="{{ route("categories.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("categories.*") ? "active" : "" }}"><x-icon name="tag" class="w-4 h-4" /> Kategori</a>
    <a href="{{ route("inventory.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("inventory.*") ? "active" : "" }}"><x-icon name="layers" class="w-4 h-4" /> Stok Barang</a>
    <a href="{{ route("stock-opnames.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("stock-opnames.*") ? "active" : "" }}"><x-icon name="layers" class="w-4 h-4" /> Stock Opname</a>
    <a href="{{ route("suppliers.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("suppliers.*") ? "active" : "" }}"><x-icon name="users" class="w-4 h-4" /> Supplier</a>

    <!-- PEMBELIAN -->
    <a href="{{ route("purchases.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("purchases.*") ? "active" : "" }}"><x-icon name="truck" class="w-4 h-4" /> Pembelian</a>

    <!-- LAINNYA -->
    <div class="px-3 pt-5 pb-1 text-[10px] font-semibold uppercase tracking-wider text-text-secondary">Lainnya</div>
    <a href="{{ route("lab.tracker") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("lab.tracker") ? "navlink-active" : "" }}"><x-icon name="flask" class="w-4 h-4" /> Servis Lab</a>
    <a href="{{ route("reports.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("reports.*") ? "navlink-active" : "" }}"><x-icon name="chart" class="w-4 h-4" /> Laporan Penjualan</a>
    <a href="{{ route("settings.store") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("settings.store") ? "navlink-active" : "" }}"><x-icon name="store" class="w-4 h-4" /> Profil Toko</a>
    <a href="{{ route("settings.staff") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("settings.staff") ? "navlink-active" : "" }}"><x-icon name="users" class="w-4 h-4" /> Staf &amp; Hak Akses</a>
    <a href="{{ route("billing.index") }}" @click="drawerOpen = false" class="navlink {{ request()->routeIs("billing.*") ? "navlink-active" : "" }}"><x-icon name="wallet" class="w-4 h-4" /> Paket &amp; Langganan</a>

    <div class="mt-5 border-t border-line pt-4">
      <form action="{{ route("logout") }}" method="POST">
        @csrf
        <button type="submit" @click="drawerOpen = false" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-left text-sm text-text-main hover:bg-canvas hover:text-emerald rounded-xl transition">
          <x-icon name="logout" class="w-4 h-4" /> Keluar
        </button>
      </form>
    </div>
  </nav>
</aside>
<div class="flex-1 min-w-0 flex flex-col">

  <header class="sticky top-0 z-20 bg-emerald/5 backdrop-blur border-b border-emerald/10">
    <div class="px-4 lg:px-6 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <button @click="drawerOpen = true"
                class="lg:hidden p-2 text-text-main hover:text-emerald rounded-lg hover:bg-emerald/5 transition"
                aria-label="Buka menu">
          <x-icon name="menu" class="w-5 h-5" />
        </button>
        <div>
          <h1 class="text-sm font-semibold text-text-main leading-tight">{{ $title ?? "Dashboard" }}</h1>
          <div class="text-[11px] text-text-secondary">{{ now()->translatedFormat("d M Y") }}</div>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <span class="hidden sm:block px-3 py-1.5 rounded-lg bg-emerald/5 text-[11px] font-medium text-emerald-dark border border-emerald/10">
          {{ app("activeStore")->get()?->name ?? "-" }}
        </span>
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald/5 border border-emerald/10">
          <div class="w-7 h-7 rounded-full bg-emerald text-white flex items-center justify-center text-[10px] font-bold shadow-sm shadow-emerald/20">
            {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
          </div>
          <span class="hidden sm:inline text-[11px] font-medium text-text-main">{{ auth()->user()?->name ?? "User" }}</span>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
    @if (session("error"))
      <x-alert type="error" :message="session('error')" />
    @endif
    @if (session("success"))
      <x-alert type="success" :message="session('success')" />
    @endif
    {{ $slot }}
  </main>

</div>

@livewireScripts
</body>
</html>
