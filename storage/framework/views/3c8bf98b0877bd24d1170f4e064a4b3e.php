<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Detail Stock Opname: '.e($opname->code).'','description' => 'Ringkasan hasil penghitungan fisik stok dan selisih dengan stok sistem']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Detail Stock Opname: '.e($opname->code).'','description' => 'Ringkasan hasil penghitungan fisik stok dan selisih dengan stok sistem']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('stock-opnames.index')); ?>" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                    <span>&larr;</span> Kembali
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($opname->status === 'completed'): ?>
                    <a href="<?php echo e(route('export.stock-opname')); ?>?opname_id=<?php echo e($opname->id); ?>" target="_blank" class="btn-outline px-4 py-2 text-sm flex items-center gap-1.5">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'download','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'download','class' => 'w-4 h-4']); ?>
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
<?php endif; ?> Ekspor CSV
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

    <!-- Info opname -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Kode Opname</p>
                <p class="text-lg font-semibold text-text-main"><?php echo e($opname->code); ?></p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Tanggal</p>
                <p class="text-lg font-semibold text-text-main"><?php echo e($opname->conducted_at?->format('d/m/Y') ?? '-'); ?></p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Status</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                    <?php echo e($opname->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($opname->status === 'cancelled' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700')); ?>">
                    <?php echo e(ucfirst($opname->status)); ?>

                </span>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Oleh</p>
                <p class="text-lg font-semibold text-text-main"><?php echo e($opname->conductor?->name ?? '-'); ?></p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Selesai</p>
                <p class="text-lg font-semibold text-text-main"><?php echo e($opname->completed_at?->format('d/m/Y H:i') ?? '-'); ?></p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Total Item</p>
                <p class="text-lg font-semibold text-text-main"><?php echo e($opname->items->count()); ?></p>
            </div>
        </div>
    </div>

    <!-- Ringkasan selisih -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-4">Ringkasan Selisih Stok</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Total Item</p>
                <p class="text-lg font-semibold text-text-main"><?php echo e($opname->items->count()); ?></p>
            </div>
            <div class="p-4 rounded-lg bg-emerald-50">
                <p class="text-[11px] uppercase tracking-wider text-emerald-700 font-bold">Tidak Ada Selisih</p>
                <p class="text-lg font-semibold text-emerald-700"><?php echo e($opname->items->where('diff', 0)->count()); ?></p>
            </div>
            <div class="p-4 rounded-lg bg-sky-50">
                <p class="text-[11px] uppercase tracking-wider text-sky-700 font-bold">Selisih Lebih</p>
                <p class="text-lg font-semibold text-sky-700"><?php echo e($opname->items->where('diff', '>', 0)->count()); ?></p>
            </div>
            <div class="p-4 rounded-lg bg-red-50">
                <p class="text-[11px] uppercase tracking-wider text-red-700 font-bold">Selisih Kurang</p>
                <p class="text-lg font-semibold text-red-700"><?php echo e($opname->items->where('diff', '<', 0)->count()); ?></p>
            </div>
        </div>
    </div>

    <!-- Tabel detail item -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Produk</th>
                        <th class="py-3 px-4 text-center">SKU</th>
                        <th class="py-3 px-4 text-center">Stok Sistem</th>
                        <th class="py-3 px-4 text-center">Hitung Fisik</th>
                        <th class="py-3 px-4 text-right">Selisih</th>
                        <th class="py-3 px-4 text-right">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $opname->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $diff = (int) ($it->counted_qty ?? 0) - (int) $it->system_qty;
                            $badgeClass = $diff === 0 ? 'bg-emerald-100 text-emerald-700' : ($diff > 0 ? 'bg-sky-50 text-sky-700' : 'bg-red-50 text-red-700');
                            $badgeLabel = $diff === 0 ? 'Cocok' : ($diff > 0 ? 'Lebih' : 'Kurang');
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-semibold text-text-main text-xs sm:text-sm"><?php echo e($it->product?->name ?? '-'); ?></div>
                                <div class="text-[11px] text-slate-500"><?php echo e($it->product?->sku ?? 'Tanpa SKU'); ?></div>
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-xs"><?php echo e($it->system_qty); ?></td>
                            <td class="py-3 px-4 text-center font-mono text-xs"><?php echo e($it->counted_qty ?? 0); ?></td>
                            <td class="py-3 px-4 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold <?php echo e($badgeClass); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($diff > 0): ?>+<?php echo e($diff); ?><?php else: ?><?php echo e($diff); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold <?php echo e($badgeClass); ?>">
                                    <?php echo e($badgeLabel); ?>

                                </span>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-slate-400">
                                Tidak ada item pada opname ini.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/stock-opnames/show.blade.php ENDPATH**/ ?>