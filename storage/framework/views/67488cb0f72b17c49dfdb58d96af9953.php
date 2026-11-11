<div>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => ''.e($customer->name).'','description' => 'Detail pelanggan, resep, dan transaksi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($customer->name).'','description' => 'Detail pelanggan, resep, dan transaksi']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('customers.index')); ?>" wire:navigate class="btn-ghost px-4 py-2 text-sm">Kembali</a>
            <a href="<?php echo e(route('customers.edit', $customer->id)); ?>" wire:navigate class="btn-primary px-4 py-2 text-sm">Ubah Data</a>
            <a href="<?php echo e(route('prescriptions.create', $customer->id)); ?>" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Resep Baru</a>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>

    <!-- Info Pelanggan -->
    <div class="card p-5">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-accent text-white flex items-center justify-center text-lg font-bold">
                <?php echo e(strtoupper(substr($customer->name, 0, 1))); ?>

            </div>
            <div>
                <div class="text-lg font-semibold text-text-main"><?php echo e($customer->name); ?></div>
                <div class="text-[11px] text-text-secondary flex items-center gap-1">
                    <span>📞</span> <?php echo e($customer->phone ?? '-'); ?>

                </div>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customer->address || $customer->birth_date): ?>
            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customer->address): ?>
                    <div class="flex items-center gap-2 text-text-secondary">
                        <span>📍</span> <?php echo e($customer->address); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customer->birth_date): ?>
                    <div class="flex items-center gap-2 text-text-secondary">
                        <span>🎂</span> <?php echo e(\Carbon\Carbon::parse($customer->birth_date)->format('d M Y')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Riwayat Resep -->
    <div class="card p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-text-main">Riwayat Resep</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customer->prescriptions->count()): ?>
                <a href="#" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customer->prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="rounded-lg border border-line p-4 text-sm mb-3">
                <div class="font-bold text-text-main"><?php echo e($r->checked_at?->format('d M Y') ?? $r->created_at->format('d M Y')); ?></div>
                <div class="grid sm:grid-cols-2 gap-2 mt-2">
                    <div class="bg-[#EFF6FF]/50 rounded-lg p-3 border border-[#EFF6FF]">
                        <div class="text-[10px] text-accent font-semibold uppercase">OD (Kanan)</div>
                        <div class="text-text-main">SPH <?php echo e($r->od_sph ?? '-'); ?> • CYL <?php echo e($r->od_cyl ?? '-'); ?> • AXIS <?php echo e($r->od_axis ?? '-'); ?> • ADD <?php echo e($r->od_add ?? '-'); ?></div>
                    </div>
                    <div class="bg-[#EFF6FF]/50 rounded-lg p-3 border border-[#EFF6FF]">
                        <div class="text-[10px] text-accent font-semibold uppercase">OS (Kiri)</div>
                        <div class="text-text-main">SPH <?php echo e($r->os_sph ?? '-'); ?> • CYL <?php echo e($r->os_cyl ?? '-'); ?> • AXIS <?php echo e($r->os_axis ?? '-'); ?> • ADD <?php echo e($r->os_add ?? '-'); ?></div>
                    </div>
                </div>
                <div class="text-[11px] text-text-secondary mt-2 flex items-center justify-between">
                    <span>PD <?php echo e($r->pd ?? '-'); ?> • <?php echo e($r->notes ?? '-'); ?></span>
                    <a href="<?php echo e(route('print.prescription', $r->id)); ?>" target="_blank" class="text-accent font-bold">Cetak</a>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p class="text-sm text-text-secondary text-center py-4">Belum ada resep untuk pelanggan ini.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Transaksi Terakhir -->
    <div class="card p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-text-main">Transaksi Terakhir</h2>
            <a href="<?php echo e(route('sales.index')); ?>" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua</a>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customer->sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="flex justify-between text-sm py-2 border-b border-line last:border-0">
                <span class="text-text-main"><?php echo e($s->code); ?></span>
                <span class="text-right font-semibold text-text-main">Rp <?php echo e(number_format($s->total, 0, ',', '.')); ?></span>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p class="text-sm text-text-secondary text-center py-2">Belum ada transaksi.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/customers/show.blade.php ENDPATH**/ ?>