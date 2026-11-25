<?php
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $presets = ['today' => 'Hari ini', '7d' => '7 hari', '30d' => '30 hari', 'all' => 'Semua'];
    $badgeClass = fn ($st) => match ($st) {
        'lunas' => 'success',
        'dp' => 'info',
        default => 'warning',
    };
?>

<div>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Riwayat Transaksi','description' => 'Semua penjualan toko Anda']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Riwayat Transaksi','description' => 'Semua penjualan toko Anda']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('pos.cashier')); ?>" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Transaksi Baru</a>
            <a href="<?php echo e(route('export.sales', array_filter(['from' => $from, 'to' => $to]))); ?>" class="btn-ghost px-4 py-2 text-sm text-xs">⬇ Ekspor CSV</a>
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

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Transaksi</p>
            <p class="text-lg font-bold text-emerald-800"><?php echo e(number_format($summary['count'])); ?></p>
            <p class="text-[11px] text-emerald-600"><?php echo e($summary['lunas']); ?> lunas</p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Pendapatan</p>
            <p class="text-lg font-bold text-emerald-800"><?php echo e($rp($summary['revenue'])); ?></p>
            <p class="text-[11px] text-emerald-600">Diskon <?php echo e($rp($summary['discount'])); ?> • Retur <?php echo e($rp($summary['refund'])); ?></p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Sudah Dibayar</p>
            <p class="text-lg font-bold text-emerald-800"><?php echo e($rp($summary['paid'])); ?></p>
        </div>
        <div class="<?php echo e($summary['outstanding'] > 0 ? 'bg-red-50 border-red-100' : 'bg-emerald-50 border-emerald-100'); ?> rounded-xl p-3 border">
            <p class="text-xs <?php echo e($summary['outstanding'] > 0 ? 'text-red-600' : 'text-emerald-600'); ?> font-medium">Sisa Tagihan</p>
            <p class="text-lg font-bold <?php echo e($summary['outstanding'] > 0 ? 'text-red-700' : 'text-emerald-800'); ?>"><?php echo e($rp($summary['outstanding'])); ?></p>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3 mb-4">
        <?php if (isset($component)) { $__componentOriginal1c4b45f62348de9b6fa41ee823d3fa96 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1c4b45f62348de9b6fa41ee823d3fa96 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-input','data' => ['model' => 'search','placeholder' => 'Cari kode atau nama pelanggan…','label' => 'Cari transaksi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'search','placeholder' => 'Cari kode atau nama pelanggan…','label' => 'Cari transaksi']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1c4b45f62348de9b6fa41ee823d3fa96)): ?>
<?php $attributes = $__attributesOriginal1c4b45f62348de9b6fa41ee823d3fa96; ?>
<?php unset($__attributesOriginal1c4b45f62348de9b6fa41ee823d3fa96); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1c4b45f62348de9b6fa41ee823d3fa96)): ?>
<?php $component = $__componentOriginal1c4b45f62348de9b6fa41ee823d3fa96; ?>
<?php unset($__componentOriginal1c4b45f62348de9b6fa41ee823d3fa96); ?>
<?php endif; ?>

        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="paymentStatus" class="input">
                <option value="">Semua status</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <option value="<?php echo e($opt); ?>"><?php echo e(ucfirst($opt)); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>

            <div class="flex items-center gap-1.5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $presets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button type="button" wire:click="setRange('<?php echo e($key); ?>')"
                            class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors
                                   <?php echo e($activeRange === $key ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'); ?>">
                        <?php echo e($label); ?>

                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <div class="flex items-center gap-1.5">
                <input type="date" wire:model.live="from"
                       class="input text-xs px-2.5 py-1.5">
                <span class="text-slate-400 text-[10px]">s/d</span>
                <input type="date" wire:model.live="to"
                       class="input text-xs px-2.5 py-1.5">
                <button type="button" wire:click="resetFilter"
                        class="text-[11px] text-slate-500 hover:text-slate-700 underline ml-1">Reset</button>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity">
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    
                    <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
    <tr>
        <th class="py-3 px-3 text-center w-px whitespace-nowrap">No</th>
        <th class="py-3 px-3 text-left w-40 whitespace-nowrap">Transaksi</th>
        <th class="py-3 px-3 text-left w-40 whitespace-nowrap">Pelanggan</th>
        <th class="py-3 px-3 text-right w-28 whitespace-nowrap">Total</th>
        <th class="py-3 px-3 text-right w-28 whitespace-nowrap">Dibayar</th>
        <th class="py-3 px-3 text-right w-28 whitespace-nowrap">Sisa</th>
        <th class="py-3 px-3 text-left w-28 whitespace-nowrap">Kasir</th>
        <th class="py-3 px-3 text-left w-px whitespace-nowrap">Status</th>
        <th class="py-3 px-3 text-right w-px whitespace-nowrap">Aksi</th>
    </tr>
</thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $refund = (float) $s->refund_total;
                                $total = max(0, (float) $s->total - $refund);
                                $paid = max(0, (float) $s->paid_total - $refund);
                                $sisa = max(0, $total - $paid);
                                $returnStatus = $refund > 0
                                    ? ($refund >= (float) $s->total ? 'Diretur' : 'Retur sebagian')
                                    : null;
                            ?>
                            <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'sale-'.e($s->id).''; ?>wire:key="sale-<?php echo e($s->id); ?>" class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-xs font-medium text-slate-500">
                                    <?php echo e($sales->firstItem() + $loop->index); ?>

                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-text-main"><?php echo e($s->code); ?></div>
                                    <p class="text-[11px] text-text-secondary"><?php echo e($s->sold_at?->translatedFormat('d M Y, H:i') ?? '-'); ?></p>
                                </td>
                                <td class="py-3 px-4 font-medium text-text-main"><?php echo e($s->customer?->name ?? '-'); ?></td>
                                <td class="py-3 px-4 text-[11px] font-semibold text-text-main text-right"><?php echo e($rp($total)); ?></td>
                                <td class="py-3 px-4 text-[11px] text-right <?php echo e($sisa <= 0 ? 'text-success' : 'text-error'); ?>"><?php echo e($rp($paid)); ?></td>
                                <td class="py-3 px-4 text-[11px] text-right <?php echo e($sisa <= 0 ? 'text-slate-400' : 'text-error'); ?>"><?php echo e($sisa > 0 ? $rp($sisa) : '-'); ?></td>
                                <td class="py-3 px-4 text-[11px] text-text-secondary"><?php echo e($s->cashier?->name ?? '-'); ?></td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => ucfirst($s->payment_status),'class' => $badgeClass($s->payment_status)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(ucfirst($s->payment_status)),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($badgeClass($s->payment_status))]); ?>
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
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($returnStatus): ?>
                                            <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $returnStatus,'class' => $refund >= (float) $s->total ? 'error' : 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($returnStatus),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($refund >= (float) $s->total ? 'error' : 'warning')]); ?>
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
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="<?php echo e(route('sales.show', $s->id)); ?>" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-400 text-sm">Tidak ada transaksi pada filter ini.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <?php echo e($sales->links()); ?>

    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/sales/history.blade.php ENDPATH**/ ?>