<div class="space-y-5">
    <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <div>
            <h1 class="text-lg font-bold tracking-tight text-slate-900">Laporan Histori Stok</h1>
            <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi pergerakan stok masuk, keluar, retur, dan penyesuaian toko</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="<?php echo e(route('export.stock-history', ['from' => $from, 'to' => $to, 'type' => $type, 'search' => $search, 'product_id' => $productId])); ?>"
               class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg border border-[#e2e8f0] bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition-colors">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'download','class' => 'w-3.5 h-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'download','class' => 'w-3.5 h-3.5']); ?>
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
                Export CSV
            </a>
            <a href="<?php echo e(route('inventory.index')); ?>" wire:navigate
               class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg bg-[#10b981] hover:bg-[#0d9488] text-xs font-semibold text-white transition-colors">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'layers','class' => 'w-3.5 h-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'layers','class' => 'w-3.5 h-3.5']); ?>
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
                Kelola Stok
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Mutasi</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900 tabular-nums"><?php echo e(number_format($summary['count'])); ?></p>
            <p class="mt-1 text-[11px] text-slate-500">Catatan pergerakan stok</p>
        </div>
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Stok Masuk</p>
            <p class="mt-2 text-2xl font-extrabold text-emerald-600 tabular-nums">+<?php echo e(number_format($summary['in'])); ?></p>
            <p class="mt-1 text-[11px] text-slate-500">Pembelian, retur, &amp; tambah stok</p>
        </div>
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Stok Keluar</p>
            <p class="mt-2 text-2xl font-extrabold text-red-600 tabular-nums">-<?php echo e(number_format($summary['out'])); ?></p>
            <p class="mt-1 text-[11px] text-slate-500">Penjualan &amp; pengurangan stok</p>
        </div>
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Selisih Bersih</p>
            <p class="mt-2 text-2xl font-extrabold tabular-nums <?php echo e($summary['net'] >= 0 ? 'text-emerald-700' : 'text-red-600'); ?>">
                <?php echo e($summary['net'] > 0 ? '+' : ''); ?><?php echo e(number_format($summary['net'])); ?>

            </p>
            <p class="mt-1 text-[11px] text-slate-500">Net perubahan periode ini</p>
        </div>
    </div>

    
    <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Cari Mutasi</label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari nama produk, SKU, catatan, atau petugas..."
                       class="input text-xs py-2">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Produk</label>
                <select wire:model.live="productId" class="input text-xs py-2">
                    <option value="">Semua produk</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?> <?php echo e($p->sku ? "({$p->sku})" : ''); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Tipe Mutasi</label>
                <select wire:model.live="type" class="input text-xs py-2">
                    <option value="">Semua tipe</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $typeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($opt); ?>">
                            <?php echo e(match ($opt) {
                                'in' => 'Masuk (In)',
                                'out' => 'Keluar (Out)',
                                'adjustment' => 'Penyesuaian',
                                default => ucfirst($opt),
                            }); ?>

                        </option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1 min-w-0">
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Periode</label>
                    <div class="flex items-center gap-1">
                        <input type="date" wire:model.live="from" class="input text-xs px-2 py-2">
                        <span class="text-slate-400 text-[10px]">-</span>
                        <input type="date" wire:model.live="to" class="input text-xs px-2 py-2">
                    </div>
                </div>
                <button type="button" wire:click="resetFilter"
                        class="h-[34px] px-2.5 rounded-lg border border-[#e2e8f0] text-xs font-medium text-slate-600 hover:bg-slate-50">
                    Reset
                </button>
            </div>
        </div>
    </div>

    
    <div wire:loading.class="opacity-60" class="transition-opacity bg-white rounded-[10px] border border-[#e2e8f0] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead class="bg-[#f4f7f9] border-b border-[#e2e8f0] text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left w-10">No</th>
                        <th class="py-3 px-4 text-left">Waktu</th>
                        <th class="py-3 px-4 text-left">Produk</th>
                        <th class="py-3 px-4 text-left">Tipe</th>
                        <th class="py-3 px-4 text-left">Referensi &amp; Catatan</th>
                        <th class="py-3 px-4 text-right">Stok Awal</th>
                        <th class="py-3 px-4 text-right">Perubahan</th>
                        <th class="py-3 px-4 text-right">Stok Akhir</th>
                        <th class="py-3 px-4 text-left">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $isPositive = $m->qty > 0;
                            $badgeClass = $m->type === 'in' || $isPositive
                                ? 'success'
                                : ($m->type === 'out' || $m->qty < 0 ? 'error' : 'warning');
                            $typeLabel = match ($m->type) {
                                'in' => 'Masuk',
                                'out' => 'Keluar',
                                'adjustment' => 'Penyesuaian',
                                default => ucfirst($m->type),
                            };
                        ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'stock-movement-'.e($m->id).''; ?>wire:key="stock-movement-<?php echo e($m->id); ?>" class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 text-slate-500 tabular-nums"><?php echo e(($movements->firstItem() ?? 0) + $loop->index); ?></td>
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap tabular-nums">
                                <?php echo e($m->created_at?->format('d/m/Y H:i') ?? '-'); ?>

                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-900"><?php echo e($m->product?->name ?? 'Produk dihapus'); ?></div>
                                <div class="text-[11px] text-slate-400 font-mono"><?php echo e($m->product?->sku ?? '-'); ?></div>
                            </td>
                            <td class="py-3 px-4">
                                <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $typeLabel,'class' => $badgeClass]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($typeLabel),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($badgeClass)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <div class="font-medium text-slate-800">
                                    <?php echo e($m->referenceLabel()); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m->reference_id): ?>
                                        <span class="text-slate-400 font-normal">#<?php echo e($m->reference_id); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m->notes): ?>
                                    <div class="text-[11px] text-slate-500"><?php echo e($m->notes); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500 tabular-nums"><?php echo e(number_format($m->stock_before)); ?></td>
                            <td class="py-3 px-4 text-right font-bold tabular-nums <?php echo e($isPositive ? 'text-emerald-600' : 'text-red-600'); ?>">
                                <?php echo e($isPositive ? '+' : ''); ?><?php echo e(number_format($m->qty)); ?>

                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900 tabular-nums"><?php echo e(number_format($m->stock_after)); ?></td>
                            <td class="py-3 px-4 text-slate-600"><?php echo e($m->creator?->name ?? '-'); ?></td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400 text-sm">
                                Belum ada histori pergerakan stok pada filter ini.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <?php if (isset($component)) { $__componentOriginald2c13c0488e53309ee86563089ed1a17 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald2c13c0488e53309ee86563089ed1a17 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.custom-pagination','data' => ['paginator' => $movements]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('custom-pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($movements)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald2c13c0488e53309ee86563089ed1a17)): ?>
<?php $attributes = $__attributesOriginald2c13c0488e53309ee86563089ed1a17; ?>
<?php unset($__attributesOriginald2c13c0488e53309ee86563089ed1a17); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald2c13c0488e53309ee86563089ed1a17)): ?>
<?php $component = $__componentOriginald2c13c0488e53309ee86563089ed1a17; ?>
<?php unset($__componentOriginald2c13c0488e53309ee86563089ed1a17); ?>
<?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/inventory/stock-history.blade.php ENDPATH**/ ?>