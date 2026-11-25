<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Hitung Fisik Stock Opname: '.e($opname->code).'','description' => 'Masukkan jumlah stok fisik riil di rak untuk dibandingkan dengan stok sistem']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Hitung Fisik Stock Opname: '.e($opname->code).'','description' => 'Masukkan jumlah stok fisik riil di rak untuk dibandingkan dengan stok sistem']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <div class="flex items-center gap-2">
                <button wire:click="cancel"
                        wire:confirm="Batalkan sesi stock opname ini?"
                        class="btn-ghost px-4 py-2 text-sm">
                    Batal
                </button>
                <button wire:click="finalize"
                        wire:confirm="Finalisasi hasil opname ini? Stok produk akan otomatis disesuaikan dengan selisih fisik."
                        class="btn-primary px-5 py-2 text-sm font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-4 h-4']); ?>
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
                    Finalisasi &amp; Sesuaikan Stok
                </button>
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

    <div class="card p-5 sm:p-6 bg-surface border border-line shadow-card rounded-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-line">
            <div>
                <h2 class="text-sm font-semibold text-text-main">Lembar Penghitungan Fisik Produk</h2>
                <p class="text-xs text-text-secondary mt-0.5">Isi kolom <strong>Hitung Fisik</strong> sesuai hasil pengecekan di etalase/gudang</p>
            </div>
            <span class="status-badge warning">Sesi Opname Terbuka</span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-line">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-canvas border-b border-line text-[11px] font-bold text-text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Produk</th>
                        <th class="py-3 px-4 text-center">Stok Sistem</th>
                        <th class="py-3 px-4 text-center">Hitung Fisik Riil</th>
                        <th class="py-3 px-4 text-right">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line bg-surface">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $opname->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $physicalCount = $counts[$it->id] ?? $it->system_qty;
                            $diff = (int)$physicalCount - (int)$it->system_qty;
                        ?>
                        <tr class="hover:bg-canvas/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-semibold text-text-main text-xs sm:text-sm"><?php echo e($it->product?->name ?? '-'); ?></div>
                                <div class="text-[11px] text-text-secondary"><?php echo e($it->product?->sku ?? 'Tanpa SKU'); ?></div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-mono font-semibold text-xs px-2.5 py-1 rounded-lg bg-canvas border border-line">
                                    <?php echo e($it->system_qty); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <input type="number"
                                       wire:model.live="counts.<?php echo e($it->id); ?>"
                                       class="w-24 text-center font-mono font-bold text-sm input py-1.5 px-2">
                            </td>
                            <td class="py-3 px-4 text-right">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($diff === 0): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-bg text-emerald-dark">
                                        Cocok (0)
                                    </span>
                                <?php elseif($diff > 0): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700">
                                        +<?php echo e($diff); ?> (Lebih)
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600 font-bold">
                                        <?php echo e($diff); ?> (Kurang)
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-text-secondary">
                                Tidak ada item pada sesi opname ini.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/stock-opnames/count.blade.php ENDPATH**/ ?>