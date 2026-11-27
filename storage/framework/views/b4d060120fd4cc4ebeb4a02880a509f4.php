<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? "Dashboard"); ?> - <?php echo e(app("activeStore")->get()?->name ?? "Optik"); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(["resources/css/app.css", "resources/js/app.js"]); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <style>
        body { font-family: 'Inter', 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<?php
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
        'nav' => 'Dashboard',
        'items' => [
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard', 'dot' => '#10b981'],
        ],
    ],
    [
        'title' => 'Transaksi',
        'items' => [
            ['route' => 'pos.cashier', 'active' => 'pos.*', 'label' => 'Kasir POS', 'icon' => 'credit-card', 'roles' => ['owner', 'kasir'], 'dot' => '#3b82f6'],
            ['route' => 'sales.index', 'active' => 'sales.*', 'label' => 'Riwayat Transaksi', 'icon' => 'wallet', 'dot' => '#10b981'],
            ['route' => 'returns.index', 'active' => 'returns.*', 'label' => 'Retur Penjualan', 'icon' => 'return', 'roles' => ['owner', 'kasir'], 'dot' => '#ef4444'],
        ],
    ],
    [
        'title' => 'Pelanggan',
        'items' => [
            ['route' => 'customers.index', 'active' => 'customers.*', 'label' => 'Daftar Pelanggan', 'icon' => 'users', 'dot' => '#3b82f6'],
            ['route' => 'warranties.index', 'active' => 'warranties.*', 'label' => 'Klaim Garansi', 'icon' => 'shield', 'dot' => '#10b981'],
        ],
    ],
    [
        'title' => 'Operasional',
        'items' => [
            ['route' => 'products.index', 'active' => 'products.*', 'label' => 'Produk & Lensa', 'icon' => 'box', 'roles' => ['owner', 'stok'], 'dot' => '#3b82f6'],
            ['route' => 'categories.index', 'active' => 'categories.*', 'label' => 'Kategori', 'icon' => 'tag', 'roles' => ['owner', 'stok'], 'dot' => '#f59e0b'],
            ['route' => 'inventory.index', 'active' => 'inventory.*', 'label' => 'Stok Lensa', 'icon' => 'layers', 'roles' => ['owner', 'stok'], 'dot' => '#10b981'],
            ['route' => 'stock-opnames.index', 'active' => 'stock-opnames.*', 'label' => 'Stock Opname', 'icon' => 'check', 'roles' => ['owner', 'stok'], 'dot' => '#10b981'],
            ['route' => 'expenses.index', 'active' => 'expenses.*', 'label' => 'Biaya Pengeluaran', 'icon' => 'wallet', 'roles' => ['owner'], 'dot' => '#ef4444'],
            ['route' => 'cash-ins.index', 'active' => 'cash-ins.*', 'label' => 'Kas Masuk', 'icon' => 'plus-circle', 'roles' => ['owner'], 'dot' => '#10b981'],
        ],
    ],
    [
        'title' => 'Laporan',
        'items' => [
            ['route' => 'reports.index', 'active' => 'reports.*', 'label' => 'Laporan Penjualan', 'icon' => 'chart', 'roles' => ['owner'], 'dot' => '#3b82f6'],
            ['route' => 'inventory.stock-history', 'active' => 'inventory.stock-history', 'label' => 'Laporan Histori Stok', 'icon' => 'history', 'roles' => ['owner', 'stok'], 'dot' => '#f59e0b'],
            ['route' => 'cash-reports.in', 'active' => 'cash-reports.in', 'label' => 'Laporan Kas Masuk', 'icon' => 'plus-circle', 'roles' => ['owner'], 'dot' => '#10b981'],
            ['route' => 'cash-reports.out', 'active' => 'cash-reports.out', 'label' => 'Laporan Kas Keluar', 'icon' => 'minus-circle', 'roles' => ['owner'], 'dot' => '#ef4444'],
        ],
    ],
    [
        'title' => 'Pengelolaan',
        'items' => [
            ['route' => 'settings.store', 'active' => 'settings.store*', 'label' => 'Profil Toko', 'icon' => 'store', 'roles' => ['owner'], 'dot' => '#10b981'],
            ['route' => 'settings.staff', 'active' => 'settings.staff*', 'label' => 'Staf & Hak Akses', 'icon' => 'users', 'roles' => ['owner'], 'dot' => '#3b82f6'],
            ['route' => 'cash-accounts.index', 'active' => 'cash-accounts.*', 'label' => 'Master Kas', 'icon' => 'wallet', 'roles' => ['owner'], 'dot' => '#f59e0b'],
            ['route' => 'billing.index', 'active' => 'billing.*', 'label' => 'Paket & Langganan', 'icon' => 'credit-card', 'roles' => ['owner'], 'dot' => '#10b981'],
        ],
    ],
];

$groups = collect($menuGroups)
    ->map(function ($g) use ($isAllowed) {
        $g['items'] = array_values(array_filter($g['items'], $isAllowed));
        $g['isActive'] = collect($g['items'])->contains(fn ($i) => request()->routeIs($i['active']));
        $g['navLabel'] = $g['nav'] ?? $g['title'];
        return $g;
    })
    ->filter(fn ($g) => count($g['items']) > 0)
    ->values();

$flatMenuItems = $groups->flatMap(function ($g) {
    return collect($g['items'])->map(fn ($i) => [
        'label' => $i['label'],
        'group' => $g['title'],
        'url' => route($i['route']),
        'dot' => $i['dot'] ?? '#10b981',
    ]);
})->values();

$storeName = app('activeStore')->get()?->name ?? 'Optik';
$userName  = auth()->user()?->name ?? 'User';
$userInit  = strtoupper(substr($userName, 0, 1));
$roleLabel = app('activeStore')->role() ?? 'Staff';
$currentTitle = $title ?? 'Dashboard';
$currentUrl = request()->url();
?>

<body class="min-h-screen antialiased bg-[#f4f7f9] text-[#0f172a]"
      x-data="{
          mobileOpen: false,
          sidebarCollapsed: localStorage.getItem('optik_sidebar_collapsed') === 'true',
          openMenu: null,
          menuQuery: '',
          searchFocused: false,
          allMenus: <?php echo \Illuminate\Support\Js::from($flatMenuItems)->toHtml() ?>,
          tabs: JSON.parse(localStorage.getItem('optik_open_tabs') || '[]'),
          currentUrl: <?php echo \Illuminate\Support\Js::from($currentUrl)->toHtml() ?>,
          currentTitle: <?php echo \Illuminate\Support\Js::from($currentTitle)->toHtml() ?>,
          init() {
              const dotColors = ['#10b981', '#3b82f6', '#f59e0b', '#0d9488'];
              if (!Array.isArray(this.tabs)) this.tabs = [];
              const exists = this.tabs.find(t => t.url === this.currentUrl);
              if (!exists) {
                  const matched = this.allMenus.find(m => m.url === this.currentUrl);
                  this.tabs.push({
                      title: this.currentTitle,
                      url: this.currentUrl,
                      dot: matched ? matched.dot : dotColors[this.tabs.length % dotColors.length]
                  });
                  if (this.tabs.length > 8) this.tabs.shift();
                  this.saveTabs();
              }
          },
          saveTabs() {
              localStorage.setItem('optik_open_tabs', JSON.stringify(this.tabs));
          },
          closeTab(url) {
              if (this.tabs.length <= 1) return;
              const idx = this.tabs.findIndex(t => t.url === url);
              this.tabs = this.tabs.filter(t => t.url !== url);
              this.saveTabs();
              if (url === this.currentUrl && this.tabs.length > 0) {
                  const next = this.tabs[Math.max(0, idx - 1)];
                  window.location.href = next.url;
              }
          },
          closeAllTabs() {
              const dashUrl = '<?php echo e(route('dashboard')); ?>';
              this.tabs = [{ title: 'Dashboard', url: dashUrl, dot: '#10b981' }];
              this.saveTabs();
              if (this.currentUrl !== dashUrl) {
                  window.location.href = dashUrl;
              }
          },
          closeOtherTabs() {
              const current = this.tabs.find(t => t.url === this.currentUrl);
              this.tabs = current ? [current] : [{ title: this.currentTitle, url: this.currentUrl, dot: '#10b981' }];
              this.saveTabs();
          },
          toggleSidebar() {
              if (window.innerWidth < 1024) {
                  this.mobileOpen = !this.mobileOpen;
              } else {
                  this.sidebarCollapsed = !this.sidebarCollapsed;
                  localStorage.setItem('optik_sidebar_collapsed', this.sidebarCollapsed);
              }
          },
          get filteredMenus() {
              const q = this.menuQuery.trim().toLowerCase();
              if (!q) return this.allMenus.slice(0, 8);
              return this.allMenus.filter(m =>
                  m.label.toLowerCase().includes(q) || m.group.toLowerCase().includes(q)
              ).slice(0, 8);
          }
      }"
      x-init="init(); $watch('mobileOpen', v => document.body.classList.toggle('overflow-hidden', v))"
      @resize.window="if (window.innerWidth >= 1024) mobileOpen = false"
      @keydown.window.ctrl.k.prevent="$refs.menuSearchInput?.focus()"
      @keydown.escape.window="mobileOpen = false; openMenu = null; searchFocused = false">

<div class="min-h-screen flex bg-[#f4f7f9]">

    <!-- ================= MOBILE OVERLAY ================= -->
    <div x-show="mobileOpen" x-cloak
         x-transition.opacity.duration.200ms
         @click="mobileOpen = false"
         class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-[1px] lg:hidden"></div>

    <!-- ================= LEFT VERTICAL SIDEBAR (~232px) ================= -->
    <aside :class="[
               sidebarCollapsed ? 'lg:w-[70px]' : 'lg:w-[232px]',
               mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
           ]"
           class="fixed inset-y-0 left-0 z-50 lg:sticky lg:top-0 lg:h-screen w-[232px] flex flex-col bg-gradient-to-b from-[#05AE72] to-[#08744E] text-white transition-all duration-200 flex-shrink-0 select-none">

        <!-- Brand Logo at Top -->
        <div class="h-14 px-3.5 flex items-center justify-between border-b border-white/15 flex-shrink-0">
            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2.5 min-w-0">
                <span class="w-8 h-8 rounded-lg bg-white/20 border border-white/25 text-white flex items-center justify-center flex-shrink-0 backdrop-blur-xs">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'w-[18px] h-[18px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'w-[18px] h-[18px]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                </span>
                <div x-show="!sidebarCollapsed" class="min-w-0">
                    <span class="block text-sm font-bold tracking-tight text-white truncate leading-tight"><?php echo e($storeName); ?></span>
                    <span class="block text-[10px] font-medium text-emerald-100/80 truncate leading-tight">Optik SaaS</span>
                </div>
            </a>
            <button @click="mobileOpen = false" type="button" aria-label="Tutup menu"
                    class="lg:hidden p-1.5 rounded-md text-white/80 hover:text-white hover:bg-white/10">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-4 h-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
            </button>
        </div>

        <!-- Menu Groups (Always Expanded) -->
        <nav class="flex-1 overflow-y-auto px-2.5 py-3 space-y-2 sidebar-scroll" aria-label="Menu utama">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($group['items']) === 1): ?>
                    <?php $only = $group['items'][0]; $isActive = request()->routeIs($only['active']); ?>
                    <a href="<?php echo e(route($only['route'])); ?>"
                       @click="mobileOpen = false"
                       title="<?php echo e($only['label']); ?>"
                       <?php if($isActive): ?> aria-current="page" <?php endif; ?>
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] transition-colors
                              <?php echo e($isActive
                                  ? 'bg-white/20 text-white font-semibold shadow-xs'
                                  : 'text-white/85 hover:bg-white/10 hover:text-white font-medium'); ?>">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $only['icon'],'class' => 'w-4 h-4 flex-shrink-0 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($only['icon']),'class' => 'w-4 h-4 flex-shrink-0 text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                        <span x-show="!sidebarCollapsed" class="truncate"><?php echo e($only['label']); ?></span>
                    </a>
                <?php else: ?>
                    <div class="pt-1">
                        <div x-show="!sidebarCollapsed"
                             class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-100/80">
                            <?php echo e($group['title']); ?>

                        </div>

                        <div class="mt-0.5 space-y-0.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php $isActive = request()->routeIs($item['active']); ?>
                                <a href="<?php echo e(route($item['route'])); ?>"
                                   @click="mobileOpen = false"
                                   title="<?php echo e($item['label']); ?>"
                                   <?php if($isActive): ?> aria-current="page" <?php endif; ?>
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] transition-colors
                                          <?php echo e($isActive
                                              ? 'bg-white/20 text-white font-semibold shadow-xs'
                                              : 'text-white/85 hover:bg-white/10 hover:text-white font-medium'); ?>">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $item['icon'],'class' => 'w-4 h-4 flex-shrink-0 '.e($isActive ? 'text-white' : 'text-emerald-100/90').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'w-4 h-4 flex-shrink-0 '.e($isActive ? 'text-white' : 'text-emerald-100/90').'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                                    <span x-show="!sidebarCollapsed" class="truncate"><?php echo e($item['label']); ?></span>
                                </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </nav>

        <!-- Compact Version Footer -->
        <div class="px-3 py-2.5 border-t border-white/15 flex items-center justify-between text-[11px] text-emerald-100/80 flex-shrink-0">
            <span x-show="!sidebarCollapsed" class="truncate font-medium">OptikSaaS v2.4</span>
            <span class="px-1.5 py-0.5 rounded bg-white/15 text-[10px] font-semibold text-white">PRO</span>
        </div>
    </aside>

    <!-- ================= RIGHT WORKSPACE COLUMN ================= -->
    <div class="flex-1 min-w-0 flex flex-col">

        <!-- ================= TOP HEADER BAR (~56px) ================= -->
        <header class="sticky top-0 z-30 h-14 bg-white border-b border-[#e2e8f0] px-4 lg:px-6 flex items-center justify-between gap-4">
            <!-- Left: Toggle + Module Title & Subtitle -->
            <div class="flex items-center gap-3 min-w-0">
                <button @click="toggleSidebar()"
                        type="button"
                        class="p-1.5 rounded-lg border border-[#e2e8f0] text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors flex-shrink-0"
                        aria-label="Toggle sidebar">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'sidebar','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'sidebar','class' => 'w-4 h-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                </button>
                <div class="min-w-0">
                    <h1 class="text-sm font-bold text-slate-900 truncate leading-tight"><?php echo e($currentTitle); ?></h1>
                    <p class="text-[11px] text-slate-500 truncate leading-tight"><?php echo e($storeName); ?> &bull; Manajemen Optik</p>
                </div>
            </div>

            <!-- Center: Search Input with Shortcut Badge -->
            <div class="hidden md:block relative w-full max-w-sm" @click.outside="searchFocused = false">
                <div class="relative flex items-center">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'w-4 h-4 text-slate-400 absolute left-3 pointer-events-none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-4 h-4 text-slate-400 absolute left-3 pointer-events-none']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                    <input x-ref="menuSearchInput"
                           type="text"
                           x-model="menuQuery"
                           @focus="searchFocused = true"
                           placeholder="Temukan menu atau fitur..."
                           class="w-full h-9 pl-9 pr-14 text-xs bg-[#f4f7f9] border border-[#e2e8f0] rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#10b981] focus:outline-none transition-colors" />
                    <span class="absolute right-2 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-[#e2e8f0] rounded">Ctrl K</span>
                </div>

                <!-- Search Dropdown -->
                <div x-show="searchFocused" x-cloak
                     x-transition.opacity.duration.100ms
                     class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-[#e2e8f0] rounded-lg shadow-lg p-1.5 z-50 max-h-72 overflow-y-auto">
                    <template x-for="item in filteredMenus" :key="item.url">
                        <a :href="item.url"
                           @click="searchFocused = false"
                           class="flex items-center justify-between px-2.5 py-2 rounded-md text-xs hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors">
                            <span class="flex items-center gap-2 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full" :style="'background:' + item.dot"></span>
                                <span x-text="item.label"></span>
                            </span>
                            <span class="text-[10px] text-slate-400" x-text="item.group"></span>
                        </a>
                    </template>
                    <div x-show="filteredMenus.length === 0" class="px-3 py-4 text-center text-xs text-slate-400">
                        Menu tidak ditemukan
                    </div>
                </div>
            </div>

            <!-- Right: Notification + Staff/Admin Profile Badge -->
            <div class="flex items-center gap-2.5 flex-shrink-0">
                <!-- Notification Bell -->
                <div class="relative" @click.outside="openMenu === 'notif' && (openMenu = null)">
                    <button type="button"
                            @click="openMenu = openMenu === 'notif' ? null : 'notif'"
                            class="relative p-2 rounded-lg border border-[#e2e8f0] text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors"
                            aria-label="Notifikasi">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bell','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-4 h-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-[#10b981]"></span>
                    </button>
                    <div x-show="openMenu === 'notif'" x-cloak
                         class="absolute right-0 top-full mt-1.5 w-64 bg-white border border-[#e2e8f0] rounded-lg shadow-lg p-3 z-50">
                        <div class="text-xs font-bold text-slate-800 pb-2 border-b border-slate-100">Notifikasi Sistem</div>
                        <div class="py-2.5 text-xs text-slate-500">
                            Semua operasional toko dan sinkronisasi inventori berjalan normal.
                        </div>
                    </div>
                </div>

                <!-- Profile Badge -->
                <div class="relative" @click.outside="openMenu === 'user' && (openMenu = null)">
                    <button type="button"
                            @click="openMenu = openMenu === 'user' ? null : 'user'"
                            class="flex items-center gap-2 pl-1.5 pr-2.5 py-1 rounded-lg border border-[#e2e8f0] bg-white hover:bg-slate-50 transition-colors"
                            aria-label="Menu akun">
                        <span class="w-7 h-7 rounded-md bg-gradient-to-br from-[#10b981] to-[#0d9488] text-white flex items-center justify-center text-xs font-bold">
                            <?php echo e($userInit); ?>

                        </span>
                        <div class="hidden sm:block text-left leading-tight">
                            <span class="block text-xs font-semibold text-slate-800 truncate max-w-[110px]"><?php echo e($userName); ?></span>
                            <span class="block text-[10px] font-medium text-slate-400 capitalize"><?php echo e($roleLabel); ?></span>
                        </div>
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chevron-down','class' => 'hidden sm:block w-3.5 h-3.5 text-slate-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'hidden sm:block w-3.5 h-3.5 text-slate-400']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                    </button>

                    <div x-show="openMenu === 'user'" x-cloak
                         class="absolute right-0 top-full mt-1.5 w-52 bg-white border border-[#e2e8f0] rounded-lg shadow-lg overflow-hidden z-50">
                        <div class="px-3.5 py-2.5 border-b border-slate-100">
                            <div class="text-xs font-bold text-slate-800 truncate"><?php echo e($userName); ?></div>
                            <div class="text-[11px] text-slate-500 capitalize"><?php echo e($roleLabel); ?> &middot; <?php echo e($storeName); ?></div>
                        </div>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="p-1.5">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-2.5 py-1.5 rounded-md text-xs font-medium text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'logout','class' => 'w-3.5 h-3.5 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout','class' => 'w-3.5 h-3.5 flex-shrink-0']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- ================= TAB BAR BELOW TOP HEADER ================= -->
        <div class="bg-white border-b border-[#e2e8f0] px-4 lg:px-6 py-1.5 flex items-center gap-2 overflow-x-auto">
            <!-- Quick-action utility buttons -->
            <div class="flex items-center gap-1.5 pr-2 border-r border-[#e2e8f0] flex-shrink-0">
                <button type="button" @click="closeAllTabs()"
                        class="px-2 py-1 rounded text-[11px] font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors whitespace-nowrap">
                    Tutup Semua
                </button>
                <button type="button" @click="closeOtherTabs()"
                        class="px-2 py-1 rounded text-[11px] font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors whitespace-nowrap">
                    Tutup Selain
                </button>
            </div>

            <!-- Closable open-tab pills -->
            <div class="flex items-center gap-1.5 min-w-0">
                <template x-for="tab in tabs" :key="tab.url">
                    <div class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border text-xs transition-colors flex-shrink-0"
                         :class="tab.url === currentUrl
                             ? 'bg-[#f4f7f9] border-[#cbd5e1] text-slate-900 font-semibold'
                             : 'bg-white border-[#e2e8f0] text-slate-600 hover:bg-slate-50'">
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" :style="'background:' + tab.dot"></span>
                        <a :href="tab.url" class="truncate max-w-[130px]" x-text="tab.title"></a>
                        <button type="button"
                                x-show="tabs.length > 1"
                                @click.prevent.stop="closeTab(tab.url)"
                                class="ml-0.5 text-slate-400 hover:text-red-500 transition-colors"
                                aria-label="Tutup tab">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-3 h-3']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- ================= MAIN WORKSPACE CANVAS ================= -->
        <main class="flex-1 w-full max-w-[1600px] mx-auto px-4 lg:px-6 py-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session("error")): ?>
                <div class="mb-4">
                    <?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => ['type' => 'error','message' => session('error')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('error'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session("success")): ?>
                <div class="mb-4">
                    <?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => ['type' => 'success','message' => session('success')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('success'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php echo e($slot); ?>

        </main>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $attributes = $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $component = $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>

<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>