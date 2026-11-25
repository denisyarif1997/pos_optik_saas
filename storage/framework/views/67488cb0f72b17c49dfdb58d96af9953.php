<div class="space-y-6">
    <?php
        $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    ?>

    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => $customer->name,'description' => 'Detail pelanggan, riwayat resep, dan transaksi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($customer->name),'description' => 'Detail pelanggan, riwayat resep, dan transaksi']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('customers.index')); ?>" wire:navigate class="btn-ghost px-3.5 py-2 text-sm">
                    Kembali
                </a>
                <a href="<?php echo e(route('customers.edit', $customer->id)); ?>" wire:navigate class="btn-ghost border border-line px-3.5 py-2 text-sm">
                    Ubah Data
                </a>
                <a href="<?php echo e(route('prescriptions.create', $customer->id)); ?>" wire:navigate class="btn-primary px-3.5 py-2 text-sm">
                    + Resep Baru
                </a>
            </div>
         <?php $__env->endSlot(); ?>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <div class="lg:col-span-4 space-y-6">
            
            <div class="card p-5">
                <div class="flex items-center gap-3.5 pb-4 border-b border-line">
                    <div class="w-11 h-11 rounded-lg bg-slate-900 text-white flex items-center justify-center text-base font-semibold shrink-0">
                        <?php echo e(strtoupper(mb_substr($customer->name, 0, 1))); ?>

                    </div>
                    <div class="min-w-0">
                        <h2 class="text-base font-semibold text-text-main truncate"><?php echo e($customer->name); ?></h2>
                        <p class="text-xs text-text-secondary tabular-nums mt-0.5 truncate">
                            <?php echo e($customer->phone ?: 'No. telepon belum tercatat'); ?>

                        </p>
                    </div>
                </div>

                <dl class="mt-4 space-y-3 text-xs">
                    <div>
                        <dt class="text-text-secondary">Tanggal Lahir</dt>
                        <dd class="text-sm font-medium text-text-main mt-0.5 tabular-nums">
                            <?php echo e($customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->translatedFormat('d M Y') : '-'); ?>

                        </dd>
                    </div>
                    <div>
                        <dt class="text-text-secondary">Alamat</dt>
                        <dd class="text-sm text-text-main mt-0.5 leading-relaxed">
                            <?php echo e($customer->address ?: '-'); ?>

                        </dd>
                    </div>
                </dl>
            </div>

            
            <div class="card p-5">
                <div class="flex items-center justify-between pb-3 border-b border-line">
                    <h2 class="text-sm font-semibold text-text-main">Transaksi Terakhir</h2>
                    <a href="<?php echo e(route('sales.index')); ?>" wire:navigate class="text-xs font-medium text-accent hover:text-accent-hover">
                        Semua
                    </a>
                </div>

                <div class="divide-y divide-line">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customer->sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="flex items-center justify-between gap-3 py-2.5 first:pt-3 last:pb-0 text-sm">
                            <div class="min-w-0">
                                <a href="<?php echo e(route('sales.show', $s->id)); ?>" wire:navigate
                                   class="font-medium text-text-main hover:text-accent truncate block">
                                    <?php echo e($s->code); ?>

                                </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($s->created_at): ?>
                                    <span class="text-[11px] text-text-secondary tabular-nums block">
                                        <?php echo e($s->created_at->translatedFormat('d M Y')); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <span class="font-semibold text-text-main tabular-nums whitespace-nowrap">
                                <?php echo e($rp($s->total)); ?>

                            </span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <p class="text-xs text-text-secondary py-6 text-center">Belum ada transaksi.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-8">
            <div class="card p-5">
                <div class="flex items-center justify-between gap-4 pb-4 border-b border-line">
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Riwayat Resep</h2>
                        <p class="text-xs text-text-secondary mt-0.5">Catatan ukuran lensa berdasarkan pemeriksaan</p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customer->prescriptions->count() > 0): ?>
                        <span class="text-xs text-text-secondary tabular-nums whitespace-nowrap">
                            <?php echo e($customer->prescriptions->count()); ?> resep
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="divide-y divide-line">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customer->prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $prescriptionDate = ($r->checked_at ?? $r->created_at)?->translatedFormat('d M Y') ?? '-';
                        ?>

                        <article class="py-4 first:pt-4 last:pb-0">
                            <div class="flex items-center justify-between gap-4 mb-3">
                                <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                    <span class="text-sm font-semibold text-text-main tabular-nums">
                                        <?php echo e($prescriptionDate); ?>

                                    </span>
                                    <span class="text-xs text-text-secondary tabular-nums">
                                        PD: <strong class="font-medium text-text-main"><?php echo e($r->pd ?? '-'); ?></strong>
                                    </span>
                                </div>
                                <a href="<?php echo e(route('print.prescription', $r->id)); ?>" target="_blank"
                                   class="text-xs font-medium text-accent hover:text-accent-hover underline underline-offset-2 whitespace-nowrap">
                                    Cetak Resep
                                </a>
                            </div>

                            <div class="overflow-x-auto border border-line rounded-lg">
                                <table class="w-full text-xs border-collapse tabular-nums">
                                    <thead class="bg-slate-50 text-text-secondary border-b border-line">
                                        <tr>
                                            <th class="py-2 px-3 text-left font-medium w-28">Mata</th>
                                            <th class="py-2 px-3 text-right font-medium">SPH</th>
                                            <th class="py-2 px-3 text-right font-medium">CYL</th>
                                            <th class="py-2 px-3 text-right font-medium">AXIS</th>
                                            <th class="py-2 px-3 text-right font-medium">ADD</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-line text-text-main">
                                        <tr>
                                            <td class="py-2 px-3 font-medium whitespace-nowrap">OD (Kanan)</td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->od_sph ?? '-'); ?></td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->od_cyl ?? '-'); ?></td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->od_axis ?? '-'); ?></td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->od_add ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-3 font-medium whitespace-nowrap">OS (Kiri)</td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->os_sph ?? '-'); ?></td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->os_cyl ?? '-'); ?></td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->os_axis ?? '-'); ?></td>
                                            <td class="py-2 px-3 text-right"><?php echo e($r->os_add ?? '-'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($r->notes): ?>
                                <p class="text-xs text-text-secondary mt-2.5 leading-relaxed">
                                    <span class="font-medium text-text-main">Catatan:</span> <?php echo e($r->notes); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </article>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="py-10 text-center">
                            <p class="text-sm text-text-secondary">Belum ada resep untuk pelanggan ini.</p>
                            <a href="<?php echo e(route('prescriptions.create', $customer->id)); ?>" wire:navigate
                               class="inline-block mt-3 text-xs font-medium text-accent hover:text-accent-hover underline underline-offset-2">
                                Buat resep pertama
                            </a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/customers/show.blade.php ENDPATH**/ ?>