<?php
    $hour = now()->hour;
    $greeting = match (true) {
        $hour < 11 => 'Selamat pagi',
        $hour < 15 => 'Selamat siang',
        $hour < 18 => 'Selamat sore',
        default => 'Selamat malam',
    };
?>

<div class="space-y-6">
    <section class="relative isolate overflow-hidden rounded-3xl bg-linear-to-br from-emerald-dark via-emerald to-emerald-hover p-5 text-white shadow-lg shadow-emerald/15 sm:p-7">
        <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-24 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-28 right-1/3 h-56 w-56 rounded-full bg-emerald-soft/20 blur-3xl"></div>

        <div class="relative grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-semibold tracking-wide text-white/90">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-200"></span>
                    DASHBOARD TOKO
                </div>
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl"><?php echo e($greeting); ?>, <?php echo e(auth()->user()?->name ?? 'Pengguna'); ?></h2>
                <p class="mt-2 max-w-xl text-sm leading-relaxed text-white/80 sm:text-base">
                    Pantau penjualan, pesanan, dan stok <?php echo e($store->name); ?> dalam satu tampilan.
                </p>

                <div class="mt-5 flex flex-wrap gap-2.5">
                    <a href="<?php echo e(route('pos.cashier')); ?>" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-emerald-dark shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-bg">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'credit-card','class' => 'h-4 w-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'h-4 w-4']); ?>
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
                        Transaksi Baru
                    </a>
                    <a href="<?php echo e(route('sales.index')); ?>" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'history','class' => 'h-4 w-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'history','class' => 'h-4 w-4']); ?>
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
                        Riwayat Penjualan
                    </a>
                </div>
            </div>

            <div class="relative min-w-0 rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm sm:p-5 lg:w-72">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-white/75">Penjualan hari ini</p>
                        <p class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Rp <?php echo e(number_format($todaySales, 0, ',', '.')); ?></p>
                        <!-- <p class="mt-1 text-[11px] text-white/75">Nilai bersih setelah retur disetujui</p> -->
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/15">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chart','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chart','class' => 'h-5 w-5']); ?>
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
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-white/15 pt-3">
                    <span class="text-xs text-white/80">Perbandingan penjualan harian</span>
                    <span class="rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-semibold">
                        <?php echo e($growth >= 0 ? 'Naik' : 'Turun'); ?> <?php echo e(abs($growth)); ?>% dari kemarin
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section aria-labelledby="quick-actions-title">
        <div class="mb-3 flex items-end justify-between gap-3">
            <div>
                <h2 id="quick-actions-title" class="text-base font-bold text-text-main">Akses cepat</h2>
                <p class="mt-0.5 text-xs text-text-secondary">Buka fitur yang sering digunakan</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <a href="<?php echo e(route('customers.create')); ?>" class="group flex items-center gap-3 rounded-2xl border border-line bg-surface p-3.5 shadow-card transition hover:-translate-y-0.5 hover:border-emerald-soft hover:shadow-hover">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-bg text-emerald-dark transition group-hover:bg-emerald group-hover:text-white">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'users','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'users','class' => 'h-5 w-5']); ?>
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
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold text-text-main">Pelanggan</span>
                    <span class="block text-[11px] text-text-secondary">Tambah data</span>
                </span>
            </a>
            <a href="<?php echo e(route('products.create')); ?>" class="group flex items-center gap-3 rounded-2xl border border-line bg-surface p-3.5 shadow-card transition hover:-translate-y-0.5 hover:border-emerald-soft hover:shadow-hover">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700 transition group-hover:bg-sky-600 group-hover:text-white">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'box','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'box','class' => 'h-5 w-5']); ?>
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
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold text-text-main">Produk</span>
                    <span class="block text-[11px] text-text-secondary">Kelola katalog</span>
                </span>
            </a>
            <a href="<?php echo e(route('inventory.index')); ?>" class="group flex items-center gap-3 rounded-2xl border border-line bg-surface p-3.5 shadow-card transition hover:-translate-y-0.5 hover:border-emerald-soft hover:shadow-hover">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700 transition group-hover:bg-amber-500 group-hover:text-white">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'layers','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'layers','class' => 'h-5 w-5']); ?>
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
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold text-text-main">Inventori</span>
                    <span class="block text-[11px] text-text-secondary">Cek ketersediaan</span>
                </span>
            </a>
            <a href="<?php echo e(route('optical-orders.index')); ?>" class="group flex items-center gap-3 rounded-2xl border border-line bg-surface p-3.5 shadow-card transition hover:-translate-y-0.5 hover:border-emerald-soft hover:shadow-hover">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-700 transition group-hover:bg-violet-600 group-hover:text-white">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'h-5 w-5']); ?>
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
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold text-text-main">Pesanan</span>
                    <span class="block text-[11px] text-text-secondary">Layanan optik</span>
                </span>
            </a>
        </div>
    </section>

    <section aria-label="Ringkasan operasional" class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        <article class="rounded-2xl border border-line bg-surface p-4 shadow-card transition hover:shadow-hover sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-text-secondary">Transaksi</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-text-main"><?php echo e(number_format($todayCount)); ?></p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-bg text-emerald-dark">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'credit-card','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'h-5 w-5']); ?>
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
            </div>
            <p class="mt-3 text-[11px] text-text-secondary">Tercatat hari ini</p>
        </article>

        <article class="rounded-2xl border border-line bg-surface p-4 shadow-card transition hover:shadow-hover sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-text-secondary">Pesanan aktif</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-text-main"><?php echo e(number_format($openOrders)); ?></p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'h-5 w-5']); ?>
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
            </div>
            <p class="mt-3 text-[11px] text-text-secondary">Belum selesai diproses</p>
        </article>

        <article class="rounded-2xl border <?php echo e($lowStock > 0 ? 'border-amber-200 bg-amber-50/50' : 'border-line bg-surface'); ?> p-4 shadow-card transition hover:shadow-hover sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-text-secondary">Stok menipis</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight <?php echo e($lowStock > 0 ? 'text-amber-800' : 'text-text-main'); ?>"><?php echo e(number_format($lowStock)); ?></p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl <?php echo e($lowStock > 0 ? 'bg-amber-100 text-amber-700' : 'bg-amber-50 text-amber-700'); ?>">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'box','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'box','class' => 'h-5 w-5']); ?>
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
            </div>
            <p class="mt-3 text-[11px] text-text-secondary"><?php echo e($lowStock > 0 ? 'Produk perlu diperiksa' : 'Stok dalam kondisi aman'); ?></p>
        </article>

        <article class="rounded-2xl border border-line bg-surface p-4 shadow-card transition hover:shadow-hover sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-text-secondary">Piutang penjualan</p>
                    <p class="mt-2 text-xl font-bold tracking-tight text-text-main">Rp <?php echo e(number_format($unpaid, 0, ',', '.')); ?></p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-700">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'wallet','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'wallet','class' => 'h-5 w-5']); ?>
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
            </div>
            <p class="mt-3 text-[11px] text-text-secondary">Transaksi belum lunas</p>
        </article>
    </section>

    <section class="grid gap-4 xl:grid-cols-[1.4fr_0.8fr]">
        <article class="rounded-2xl border border-line bg-surface p-4 shadow-card sm:p-5">
            <div class="mb-5 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-text-main">Tren penjualan</h2>
                    <p class="mt-1 text-xs text-text-secondary">Performa selama 7 hari terakhir</p>
                </div>
                <a href="<?php echo e(route('sales.index')); ?>" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-dark transition hover:bg-emerald-bg">
                    Riwayat
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="flex h-44 items-end gap-2 sm:h-52 sm:gap-4" role="img" aria-label="Grafik penjualan selama 7 hari terakhir">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekly; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $barHeight = $maxWeekly > 0 ? max(4, round($w['value'] / $maxWeekly * 100)) : 4;
                    ?>
                    <div class="group flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-2" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'weekly-sale-'.e($loop->index).''; ?>wire:key="weekly-sale-<?php echo e($loop->index); ?>">
                        <div class="flex h-full w-full max-w-12 items-end overflow-hidden rounded-t-lg bg-emerald-bg/70">
                            <div
                                class="w-full rounded-t-lg bg-emerald transition-colors group-hover:bg-emerald-dark"
                                style="height: <?php echo e($barHeight); ?>%"
                                title="<?php echo e($w['label']); ?>: Rp <?php echo e(number_format($w['value'], 0, ',', '.')); ?>"
                            ></div>
                        </div>
                        <span class="text-[10px] font-medium text-text-secondary"><?php echo e($w['label']); ?></span>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <div class="mt-4 flex items-center gap-2 border-t border-line-soft pt-3 text-[11px] text-text-secondary">
                <span class="h-2 w-2 rounded-full bg-emerald"></span>
                Nilai penjualan per hari
            </div>
        </article>

        <article class="rounded-2xl border border-line bg-surface p-4 shadow-card sm:p-5">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-text-main">Siap diambil</h2>
                    <p class="mt-1 text-xs text-text-secondary">Pesanan menunggu pelanggan</p>
                </div>
                <span class="rounded-full bg-emerald-bg px-2.5 py-1 text-xs font-bold text-emerald-dark"><?php echo e($readyOrders->count()); ?></span>
            </div>

            <div class="divide-y divide-line-soft">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $readyOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a href="<?php echo e(route('optical-orders.index')); ?>" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'ready-order-'.e($o->id).''; ?>wire:key="ready-order-<?php echo e($o->id); ?>" class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-semibold text-text-main"><?php echo e($o->code); ?></span>
                            <span class="mt-0.5 block truncate text-xs text-text-secondary"><?php echo e($o->customer?->name ?? 'Pelanggan umum'); ?></span>
                        </span>
                        <span class="status-badge success shrink-0">Siap</span>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="rounded-xl bg-canvas px-4 py-6 text-center">
                        <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-surface text-text-muted">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'h-5 w-5']); ?>
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
                        <p class="mt-2 text-sm font-semibold text-text-main">Belum ada pesanan siap</p>
                        <p class="mt-1 text-xs text-text-secondary">Pesanan siap diambil akan muncul di sini.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </article>
    </section>

    <section class="grid gap-4 xl:grid-cols-2">
        <article class="rounded-2xl border border-line bg-surface p-4 shadow-card sm:p-5">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-text-main">Perlu restock</h2>
                    <p class="mt-1 text-xs text-text-secondary">Produk yang mencapai batas stok minimum</p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lowStock > 0): ?>
                    <a href="<?php echo e(route('inventory.index')); ?>" class="shrink-0 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-dark transition hover:bg-emerald-bg">Lihat inventori &rarr;</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="divide-y divide-line-soft">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'low-stock-'.e($p->id).''; ?>wire:key="low-stock-<?php echo e($p->id); ?>" class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'box','class' => 'h-4 w-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'box','class' => 'h-4 w-4']); ?>
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
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-semibold text-text-main"><?php echo e($p->name); ?></span>
                                <span class="block text-[11px] text-text-secondary">Minimum <?php echo e($p->min_stock); ?> unit</span>
                            </span>
                        </div>
                        <span class="shrink-0 rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-bold text-amber-800"><?php echo e($p->stock); ?> tersisa</span>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="rounded-xl bg-emerald-bg/60 px-4 py-5 text-center">
                        <p class="text-sm font-semibold text-emerald-dark">Stok aman</p>
                        <p class="mt-1 text-xs text-text-secondary">Semua produk berada di atas batas minimum.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </article>

        <article class="rounded-2xl border border-line bg-surface p-4 shadow-card sm:p-5">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-text-main">Pesanan aktif</h2>
                    <p class="mt-1 text-xs text-text-secondary">Pesanan yang masih dalam proses</p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($openOrders > 0): ?>
                    <a href="<?php echo e(route('optical-orders.index')); ?>" class="shrink-0 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-dark transition hover:bg-emerald-bg">Lihat semua &rarr;</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="divide-y divide-line-soft">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $orderStatusClass = $o->status === 'siap_diambil'
                            ? 'success'
                            : (in_array($o->status, ['DIBATALKAN', 'dibatalkan'], true) ? 'error' : 'info');
                    ?>
                    <a href="<?php echo e(route('optical-orders.index')); ?>" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'pending-order-'.e($o->id).''; ?>wire:key="pending-order-<?php echo e($o->id); ?>" class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-semibold text-text-main"><?php echo e($o->code); ?></span>
                            <span class="mt-0.5 block truncate text-xs text-text-secondary"><?php echo e($o->customer?->name ?? 'Pelanggan umum'); ?></span>
                        </span>
                        <span class="status-badge <?php echo e($orderStatusClass); ?> shrink-0"><?php echo e(str_replace('_', ' ', $o->status)); ?></span>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="rounded-xl bg-canvas px-4 py-6 text-center">
                        <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-surface text-text-muted">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'h-5 w-5']); ?>
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
                        <p class="mt-2 text-sm font-semibold text-text-main">Tidak ada pesanan aktif</p>
                        <p class="mt-1 text-xs text-text-secondary">Pesanan baru akan muncul di sini.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </article>
    </section>

    <section class="overflow-hidden rounded-2xl border border-line bg-surface shadow-card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line-soft px-4 py-4 sm:px-5">
            <div>
                <h2 class="text-base font-bold text-text-main">Transaksi terbaru</h2>
                <p class="mt-1 text-xs text-text-secondary">Aktivitas penjualan terkini di toko Anda</p>
            </div>
            <a href="<?php echo e(route('sales.index')); ?>" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-dark transition hover:bg-emerald-bg">
                Semua transaksi
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[620px] text-sm">
                <thead class="bg-canvas/80">
                    <tr class="text-left">
                        <th scope="col" class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-text-muted sm:px-5">Transaksi</th>
                        <th scope="col" class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-text-muted">Waktu</th>
                        <th scope="col" class="px-4 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-text-muted">Total bersih</th>
                        <th scope="col" class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-text-muted">Status</th>
                        <th scope="col" class="px-4 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-text-muted sm:px-5">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-soft">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'dashboard-sale-'.e($s->id).''; ?>wire:key="dashboard-sale-<?php echo e($s->id); ?>" class="transition hover:bg-canvas/70">
                            <td class="px-4 py-3.5 sm:px-5">
                                <span class="block font-semibold text-text-main"><?php echo e($s->code); ?></span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-xs text-text-secondary"><?php echo e($s->sold_at->translatedFormat('d M, H:i')); ?></td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-right font-semibold text-text-main">Rp <?php echo e(number_format($s->net_total, 0, ',', '.')); ?></td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="status-badge <?php echo e($s->payment_status === 'lunas' ? 'success' : ($s->payment_status === 'dp' ? 'warning' : 'error')); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $s->payment_status))); ?>

                                    </span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($s->return_status): ?>
                                        <span class="status-badge <?php echo e($s->return_status === 'Diretur' ? 'error' : 'warning'); ?>"><?php echo e($s->return_status); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-right sm:px-5">
                                <a href="<?php echo e(route('sales.show', $s->id)); ?>" wire:navigate class="inline-flex items-center rounded-lg border border-line px-3 py-1.5 text-xs font-semibold text-text-secondary transition hover:border-emerald-soft hover:bg-emerald-bg hover:text-emerald-dark">
                                    Buka
                                </a>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center">
                                <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-canvas text-text-muted">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'history','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'history','class' => 'h-5 w-5']); ?>
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
                                <p class="mt-3 text-sm font-semibold text-text-main">Belum ada transaksi</p>
                                <p class="mt-1 text-xs text-text-secondary">Transaksi terbaru akan tampil di bagian ini.</p>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/livewire/dashboard/index.blade.php ENDPATH**/ ?>