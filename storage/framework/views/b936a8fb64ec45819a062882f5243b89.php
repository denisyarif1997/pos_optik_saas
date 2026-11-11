<?php
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $presets = ['today' => 'Hari ini', '7d' => '7 hari', '30d' => '30 hari', 'all' => 'Semua'];
    $badge = fn ($st) => match ($st) {
        'lunas' => 'bg-emerald-100 text-emerald-700',
        'dp' => 'bg-blue-100 text-blue-700',
        default => 'bg-amber-100 text-amber-700',
    };
?>

<div class="space-y-4">
    
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Riwayat Transaksi</h1>
            <p class="text-xs text-slate-500">Semua penjualan tokomu</p>
        </div>
        <a href="<?php echo e(route('pos.cashier')); ?>" wire:navigate
           class="ml-auto bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">
            + Transaksi Baru
        </a>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Transaksi</p>
            <p class="text-lg font-bold text-emerald-800"><?php echo e(number_format($summary['count'])); ?></p>
            <p class="text-[11px] text-emerald-600"><?php echo e($summary['lunas']); ?> lunas</p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Pendapatan</p>
            <p class="text-lg font-bold text-emerald-800"><?php echo e($rp($summary['revenue'])); ?></p>
            <p class="text-[11px] text-emerald-600">Diskon <?php echo e($rp($summary['discount'])); ?></p>
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

    
    <div class="bg-white rounded-xl border border-slate-200 p-3 space-y-3">
        <div class="flex flex-wrap gap-2">
            <input type="search" wire:model.live.debounce.300ms="search"
                   placeholder="Cari kode atau nama pelanggan…"
                   class="flex-1 min-w-[220px] rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-400 outline-none">
            <select wire:model.live="paymentStatus"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-400 outline-none">
                <option value="">Semua status</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <option value="<?php echo e($opt); ?>"><?php echo e(ucfirst($opt)); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $presets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <button type="button" wire:click="setRange('<?php echo e($key); ?>')"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors
                               <?php echo e($activeRange === $key ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'); ?>">
                    <?php echo e($label); ?>

                </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            <div class="flex items-center gap-1.5 ml-auto">
                <input type="date" wire:model.live="from"
                       class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs focus:ring-2 focus:ring-emerald-400 outline-none">
                <span class="text-slate-400 text-xs">s/d</span>
                <input type="date" wire:model.live="to"
                       class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs focus:ring-2 focus:ring-emerald-400 outline-none">
                <button type="button" wire:click="resetFilter"
                        class="text-xs text-slate-500 hover:text-slate-700 underline ml-1">Reset</button>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity">
        
        <div class="hidden md:block bg-white rounded-xl border border-slate-200 p-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="py-2.5 pr-3">Transaksi</th>
                        <th class="py-2.5 pr-3">Pelanggan</th>
                        <th class="py-2.5 pr-3 text-right">Total</th>
                        <th class="py-2.5 pr-3 text-right">Dibayar</th>
                        <th class="py-2.5 pr-3 text-right">Sisa</th>
                        <th class="py-2.5 pr-3">Kasir</th>
                        <th class="py-2.5 pr-3">Status</th>
                        <th class="py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $total = (float) $s->total;
                            $paid = (float) $s->paid_total;
                            $disc = (float) $s->discount_total;
                            $sisa = max(0, $total - $paid);
                        ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'sale-'.e($s->id).''; ?>wire:key="sale-<?php echo e($s->id); ?>" class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                            <td class="py-2.5 pr-3">
                                <p class="font-bold"><?php echo e($s->code); ?></p>
                                <p class="text-[11px] text-slate-400"><?php echo e($s->sold_at?->translatedFormat('d M Y, H:i') ?? '-'); ?></p>
                            </td>
                            <td class="py-2.5 pr-3 font-medium text-slate-800"><?php echo e($s->customer?->name ?? '-'); ?></td>
                            <td class="py-2.5 pr-3 text-right">
                                <p class="font-bold"><?php echo e($rp($total)); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($disc > 0): ?>
                                    <p class="text-[11px] text-slate-400">Diskon <?php echo e($rp($disc)); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="py-2.5 pr-3 text-right font-semibold <?php echo e($sisa <= 0 ? 'text-emerald-700' : 'text-amber-700'); ?>"><?php echo e($rp($paid)); ?></td>
                            <td class="py-2.5 pr-3 text-right font-bold <?php echo e($sisa <= 0 ? 'text-slate-300' : 'text-red-600'); ?>"><?php echo e($sisa > 0 ? $rp($sisa) : '-'); ?></td>
                            <td class="py-2.5 pr-3 text-slate-500 text-xs"><?php echo e($s->cashier?->name ?? '-'); ?></td>
                            <td class="py-2.5 pr-3">
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full <?php echo e($badge($s->payment_status)); ?>"><?php echo e(ucfirst($s->payment_status)); ?></span>
                            </td>
                            <td class="py-2.5 text-center">
                                <a href="<?php echo e(route('sales.show', $s->id)); ?>" wire:navigate
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-black text-white text-xs font-semibold transition-colors">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'eye','class' => 'w-3.5 h-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','class' => 'w-3.5 h-3.5']); ?>
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
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada transaksi pada filter ini.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="md:hidden space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $total = (float) $s->total;
                    $paid = (float) $s->paid_total;
                    $sisa = max(0, $total - $paid);
                ?>
                <a <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'sale-m-'.e($s->id).''; ?>wire:key="sale-m-<?php echo e($s->id); ?>" href="<?php echo e(route('sales.show', $s->id)); ?>" wire:navigate
                   class="block bg-white rounded-xl border border-slate-200 p-3 active:bg-slate-50">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-bold text-sm"><?php echo e($s->code); ?></p>
                            <p class="text-[11px] text-slate-400"><?php echo e($s->sold_at?->translatedFormat('d M Y, H:i') ?? '-'); ?></p>
                        </div>
                        <span class="shrink-0 text-[11px] font-bold px-2.5 py-1 rounded-full <?php echo e($badge($s->payment_status)); ?>"><?php echo e(ucfirst($s->payment_status)); ?></span>
                    </div>
                    <p class="text-sm text-slate-700 mt-1.5 truncate"><?php echo e($s->customer?->name ?? '-'); ?></p>
                    <div class="flex items-end justify-between mt-2 pt-2 border-t border-slate-100">
                        <div>
                            <p class="text-[11px] text-slate-400">Total</p>
                            <p class="font-bold text-sm"><?php echo e($rp($total)); ?></p>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sisa > 0): ?>
                            <div class="text-right">
                                <p class="text-[11px] text-slate-400">Sisa</p>
                                <p class="font-bold text-sm text-red-600"><?php echo e($rp($sisa)); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="bg-white rounded-xl border border-slate-200 py-8 text-center text-slate-400 text-sm">
                    Tidak ada transaksi pada filter ini.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div><?php echo e($sales->links()); ?></div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/sales/history.blade.php ENDPATH**/ ?>