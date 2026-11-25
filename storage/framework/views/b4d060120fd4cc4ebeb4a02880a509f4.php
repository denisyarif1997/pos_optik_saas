<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? "Dashboard"); ?> - <?php echo e(app("activeStore")->get()?->name ?? "Optik"); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(["resources/css/app.css", "resources/js/app.js"]); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

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
?>

    <!-- ================= TOP BAR ================= -->
    <header class="sticky top-0 z-40 bg-surface border-b border-line">
        <div class="mx-auto max-w-[1600px] h-14 px-4 lg:px-8 flex items-center gap-4">

            <!-- Hamburger (mobile) -->
            <button @click="mobileOpen = true"
                    type="button"
                    class="lg:hidden -ml-2 p-2 rounded-md text-text-secondary hover:text-text-main hover:bg-canvas"
                    aria-label="Buka menu">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'menu','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu','class' => 'w-5 h-5']); ?>
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

            <!-- Brand -->
            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2.5 min-w-0 flex-shrink-0">
                <span class="w-8 h-8 rounded-md bg-emerald text-white flex items-center justify-center flex-shrink-0">
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
                <span class="hidden sm:block text-sm font-semibold text-text-main truncate max-w-[160px]"><?php echo e($storeName); ?></span>
            </a>

            <!-- Navigasi desktop -->
            <nav class="hidden lg:flex items-stretch h-14 ml-4 gap-1" aria-label="Menu utama" @click.outside="openMenu = null">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $key = 'g' . $loop->index; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($group['items']) === 1): ?>
                        
                        <?php $only = $group['items'][0]; ?>
                        <a href="<?php echo e(route($only['route'])); ?>"
                           <?php if($group['isActive']): ?> aria-current="page" <?php endif; ?>
                           class="flex items-center px-3 -mb-px text-sm font-medium border-b-2 transition-colors
                                  <?php echo e($group['isActive']
                                      ? 'text-text-main border-emerald'
                                      : 'text-text-secondary border-transparent hover:text-text-main'); ?>">
                            <?php echo e($group['navLabel']); ?>

                        </a>
                    <?php else: ?>
                        
                        <div class="relative flex">
                            <button type="button"
                                    @click="toggle('<?php echo e($key); ?>')"
                                    :aria-expanded="openMenu === '<?php echo e($key); ?>'"
                                    class="flex items-center gap-1 px-3 -mb-px text-sm font-medium border-b-2 transition-colors
                                           <?php echo e($group['isActive']
                                               ? 'text-text-main border-emerald'
                                               : 'text-text-secondary border-transparent hover:text-text-main'); ?>"
                                    :class="openMenu === '<?php echo e($key); ?>' ? '!text-text-main' : ''">
                                <?php echo e($group['navLabel']); ?>

                                <svg class="w-3.5 h-3.5 text-text-muted transition-transform duration-150"
                                     :class="openMenu === '<?php echo e($key); ?>' ? 'rotate-180' : ''"
                                     viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 7.5l5 5 5-5" />
                                </svg>
                            </button>

                            <div x-show="openMenu === '<?php echo e($key); ?>'"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute left-0 top-full mt-1 w-60 p-1.5 bg-surface border border-line rounded-lg shadow-lg">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php $isActive = request()->routeIs($item['active']); ?>
                                    <a href="<?php echo e(route($item['route'])); ?>"
                                       <?php if($isActive): ?> aria-current="page" <?php endif; ?>
                                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-md text-sm transition-colors
                                              <?php echo e($isActive
                                                  ? 'bg-emerald-bg text-emerald-dark font-medium'
                                                  : 'text-text-main hover:bg-canvas'); ?>">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $item['icon'],'class' => 'w-4 h-4 flex-shrink-0 '.e($isActive ? '' : 'text-text-muted').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'w-4 h-4 flex-shrink-0 '.e($isActive ? '' : 'text-text-muted').'']); ?>
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
                                        <span class="truncate"><?php echo e($item['label']); ?></span>
                                    </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </nav>

            <!-- Menu user -->
            <div class="relative ml-auto" @click.outside="openMenu === 'user' && (openMenu = null)">
                <button type="button"
                        @click="toggle('user')"
                        class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full hover:bg-canvas transition-colors"
                        aria-label="Menu akun">
                    <span class="w-8 h-8 rounded-full bg-canvas border border-line text-text-main flex items-center justify-center text-xs font-semibold">
                        <?php echo e($userInit); ?>

                    </span>
                    <span class="hidden md:block text-sm font-medium text-text-main truncate max-w-[140px]"><?php echo e($userName); ?></span>
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
                        <div class="text-sm font-medium text-text-main truncate"><?php echo e($userName); ?></div>
                        <div class="text-xs text-text-secondary capitalize"><?php echo e($roleLabel); ?> &middot; <?php echo e($storeName); ?></div>
                    </div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="p-1.5">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-md text-sm text-text-main hover:bg-error/5 hover:text-error transition-colors">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'logout','class' => 'w-4 h-4 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout','class' => 'w-4 h-4 flex-shrink-0']); ?>
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
                <span class="text-sm font-semibold truncate"><?php echo e($storeName); ?></span>
            </div>
            <button @click="mobileOpen = false" type="button" aria-label="Tutup menu"
                    class="p-2 -mr-2 rounded-md text-text-secondary hover:text-text-main hover:bg-canvas">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-5 h-5']); ?>
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

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5" aria-label="Menu utama">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($group['items']) > 1): ?>
                        <div class="px-2.5 pb-1 text-xs font-medium text-text-muted"><?php echo e($group['title']); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="space-y-px">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $isActive = request()->routeIs($item['active']); ?>
                            <a href="<?php echo e(route($item['route'])); ?>"
                               @click="mobileOpen = false"
                               <?php if($isActive): ?> aria-current="page" <?php endif; ?>
                               class="flex items-center gap-3 px-2.5 h-10 rounded-md text-sm transition-colors
                                      <?php echo e($isActive
                                          ? 'bg-emerald-bg text-emerald-dark font-medium'
                                          : 'text-text-main hover:bg-canvas'); ?>">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $item['icon'],'class' => 'w-[18px] h-[18px] flex-shrink-0 '.e($isActive ? '' : 'text-text-muted').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'w-[18px] h-[18px] flex-shrink-0 '.e($isActive ? '' : 'text-text-muted').'']); ?>
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
                                <span class="truncate"><?php echo e($item['label']); ?></span>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </nav>

        <div class="p-3 border-t border-line flex items-center gap-3 flex-shrink-0">
            <span class="w-9 h-9 rounded-full bg-canvas border border-line flex items-center justify-center text-xs font-semibold flex-shrink-0"><?php echo e($userInit); ?></span>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-medium truncate leading-tight"><?php echo e($userName); ?></div>
                <div class="text-xs text-text-secondary capitalize"><?php echo e($roleLabel); ?></div>
            </div>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" aria-label="Keluar"
                        class="p-2 rounded-md text-text-secondary hover:text-error hover:bg-error/5 transition-colors">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'logout','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout','class' => 'w-5 h-5']); ?>
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
            </form>
        </div>
    </aside>

    <!-- ================= KONTEN ================= -->
    <main class="mx-auto w-full max-w-[1600px] px-4 lg:px-8 py-6">

        <div class="mb-6 flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1">
            <h1 class="text-xl font-semibold tracking-tight text-text-main"><?php echo e($title ?? "Dashboard"); ?></h1>
            <div class="text-sm text-text-secondary tabular-nums"><?php echo e(now()->translatedFormat("l, d F Y")); ?></div>
        </div>

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
</html><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>