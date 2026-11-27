<div class="space-y-6">

    
    <div class="bg-white rounded-2xl border border-emerald-100/70 shadow-sm p-5 flex flex-col lg:flex-row gap-4 lg:items-end">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'banknotes','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'banknotes','class' => 'w-6 h-6']); ?>
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
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">Laporan Toko</h1>
                <p class="text-xs text-slate-500 mt-0.5">Hanya data tokomu • mencakup penjualan tunai &amp; pesanan kacamata selesai</p>
            </div>
        </div>
        <div class="lg:ml-auto flex flex-wrap items-end gap-3">
            <label class="text-xs font-medium text-slate-600">Dari
                <input type="date" wire:model.live="from" class="mt-1.5 block rounded-xl bg-slate-50 border border-emerald-100 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 transition">
            </label>
            <label class="text-xs font-medium text-slate-600">Sampai
                <input type="date" wire:model.live="to" class="mt-1.5 block rounded-xl bg-slate-50 border border-emerald-100 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 transition">
            </label>
            <a href="<?php echo e(route('export.sales', ['from' => $from, 'to' => $to])); ?>" class="self-end text-xs font-semibold px-4 py-2.5 rounded-xl bg-emerald-500 text-white shadow-sm shadow-emerald-200 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 transition">⬇ CSV</a>
        </div>
    </div>

    
    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>Omzet penjualan
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-emerald-700">Rp <?php echo e(number_format($saleRevenue, 0, ',', '.')); ?></div>
            <div class="text-xs text-slate-400 mt-1.5"><?php echo e($saleCount); ?> transaksi • retur Rp <?php echo e(number_format($refundTotal, 0, ',', '.')); ?></div>
        </div>
        <!-- <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>Omzet optical
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-blue-600">Rp <?php echo e(number_format($opticalRevenue, 0, ',', '.')); ?></div>
            <div class="text-xs text-slate-400 mt-1.5"><?php echo e($opticalCount); ?> pesanan</div>
        </div> -->
        <div class="col-span-2 xl:col-span-1 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-md shadow-emerald-200 p-5 text-white">
            <div class="text-xs font-medium text-emerald-100">Total omzet</div>
            <div class="mt-2 text-xl font-bold tabular-nums">Rp <?php echo e(number_format($revenue, 0, ',', '.')); ?></div>
            <div class="text-xs text-emerald-100 mt-1.5"><?php echo e($count); ?> transaksi</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>Kas bersih
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-slate-900">Rp <?php echo e(number_format($collected, 0, ',', '.')); ?></div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>Piutang
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-amber-600">Rp <?php echo e(number_format($receivable, 0, ',', '.')); ?></div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <h2 class="font-semibold text-slate-900 mb-4">Laba / Rugi (Periode)</h2>
            <div class="text-sm divide-y divide-slate-100">
                <div class="flex justify-between py-2.5"><span class="text-slate-600">Omzet</span><span class="font-semibold tabular-nums text-slate-900">Rp <?php echo e(number_format($revenue, 0, ',', '.')); ?></span></div>
                <div class="flex justify-between py-2.5"><span class="text-slate-600">HPP (COGS)</span><span class="font-semibold tabular-nums text-red-600">Rp <?php echo e(number_format($cogs, 0, ',', '.')); ?></span></div>
                <div class="flex justify-between py-2.5"><span class="text-slate-600">Laba kotor</span><span class="font-semibold tabular-nums text-emerald-700">Rp <?php echo e(number_format($grossProfit, 0, ',', '.')); ?></span></div>
                <div class="flex justify-between py-2.5"><span class="text-slate-600">Pengeluaran</span><span class="font-semibold tabular-nums text-red-600">Rp <?php echo e(number_format($expenseTotal, 0, ',', '.')); ?></span></div>
            </div>
            <div class="mt-3 flex items-center justify-between rounded-xl px-4 py-3.5 <?php echo e($netProfit >= 0 ? 'bg-emerald-50 border border-emerald-100' : 'bg-red-50 border border-red-100'); ?>">
                <span class="font-semibold text-slate-800">Laba bersih</span>
                <span class="font-bold text-lg tabular-nums <?php echo e($netProfit >= 0 ? 'text-emerald-700' : 'text-red-600'); ?>">Rp <?php echo e(number_format($netProfit, 0, ',', '.')); ?></span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <h2 class="font-semibold text-slate-900 mb-4">Produk terlaris</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'top-product-'.e($t->product_id).''; ?>wire:key="top-product-<?php echo e($t->product_id); ?>" class="flex items-center gap-3 text-sm py-2.5 border-b border-slate-100 last:border-0">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold flex items-center justify-center shrink-0"><?php echo e($loop->iteration); ?></span>
                    <span class="font-semibold text-slate-800 min-w-0 truncate"><?php echo e($t->product?->name ?? '-'); ?></span>
                    <span class="text-xs text-slate-400 font-normal shrink-0">× <?php echo e($t->qty); ?></span>
                    <span class="ml-auto font-medium tabular-nums text-slate-700">Rp <?php echo e(number_format($t->omzet, 0, ',', '.')); ?></span>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="text-sm text-slate-400 text-center py-8">Belum ada penjualan pada periode ini.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Metode Pembayaran -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
        <h2 class="font-semibold text-slate-900 mb-4">Metode pembayaran</h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <?php
                $methodLabels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'card' => 'Kartu'];
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $methodLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'payment-method-'.e($method).''; ?>wire:key="payment-method-<?php echo e($method); ?>" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs text-slate-500 font-medium"><?php echo e($label); ?></div>
                        <div class="mt-1 text-base font-bold tabular-nums text-slate-900 truncate">Rp <?php echo e(number_format($paymentMethods[$method] ?? 0, 0, ',', '.')); ?></div>
                    </div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                        <?php switch($method):
                            case ('cash'): ?> bg-emerald-100 text-emerald-600 <?php break; ?>
                            <?php case ('transfer'): ?> bg-blue-100 text-blue-600 <?php break; ?>
                            <?php case ('qris'): ?> bg-purple-100 text-purple-600 <?php break; ?>
                            <?php default: ?> bg-slate-100 text-slate-500 <?php break; ?>
                        <?php endswitch; ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($method):
                            case ('cash'): ?> <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'banknotes','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'banknotes','class' => 'w-5 h-5']); ?>
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
<?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                            <?php case ('transfer'): ?> <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'refresh-cw','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'refresh-cw','class' => 'w-5 h-5']); ?>
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
<?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                            <?php case ('qris'): ?> <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'qrcode','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'qrcode','class' => 'w-5 h-5']); ?>
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
<?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                            <?php default: ?> <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'credit-card','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'w-5 h-5']); ?>
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
<?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                        <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($paymentMethods) > 0): ?>
                <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50 flex items-center justify-between">
                    <div class="min-w-0">
                        <div class="text-xs text-emerald-700 font-medium">Total</div>
                        <div class="mt-1 text-base font-bold tabular-nums text-emerald-800 truncate">Rp <?php echo e(number_format(array_sum($paymentMethods), 0, ',', '.')); ?></div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Tren Harian -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
        <h2 class="font-semibold text-slate-900 mb-4">Tren harian</h2>
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500">
                    <tr>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-right">Penjualan</th>
                        <!-- <th class="py-3 px-4 text-right">Optical</th> -->
                        <th class="py-3 px-4 text-right">Total</th>
                        <th class="py-3 px-4 text-center">Jumlah transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $dailyTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'daily-trend-'.e($day['date']).''; ?>wire:key="daily-trend-<?php echo e($day['date']); ?>" class="hover:bg-emerald-50/40 transition-colors">
                            <td class="py-3 px-4 font-medium text-slate-800"><?php echo e(\Carbon\Carbon::parse($day['date'])->format('d M')); ?></td>
                            <td class="py-3 px-4 text-right tabular-nums text-slate-600">Rp <?php echo e(number_format($day['sale'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-right tabular-nums text-blue-600">Rp <?php echo e(number_format($day['optical'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-right tabular-nums font-semibold text-slate-900">Rp <?php echo e(number_format($day['sale'] + $day['optical'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[1.75rem] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold"><?php echo e($day['count']); ?></span>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="5" class="py-10 text-center text-sm text-slate-400">Belum ada data pada periode ini.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaksi Terkini -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-900">Transaksi terkini</h2>
            <a href="<?php echo e(route('export.transactions', ['from' => $from, 'to' => $to])); ?>"
               class="text-xs font-semibold px-3.5 py-2 rounded-xl bg-emerald-500 text-white shadow-sm shadow-emerald-200 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 transition">
                ⬇ Export CSV
            </a>
        </div>
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500">
                    <tr>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Jenis</th>
                        <th class="py-3 px-4 text-left">Kode</th>
                        <th class="py-3 px-4 text-left">Pelanggan</th>
                        <th class="py-3 px-4 text-left">Nama kas</th>
                        <th class="py-3 px-4 text-left">Diproses oleh</th>
                        <th class="py-3 px-4 text-right">Total</th>
                        <th class="py-3 px-4 text-right">Dibayar</th>
                        <th class="py-3 px-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'recent-transaction-'.e($t['type']).'-'.e($t['code']).''; ?>wire:key="recent-transaction-<?php echo e($t['type']); ?>-<?php echo e($t['code']); ?>" class="hover:bg-emerald-50/40 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap text-slate-600"><?php echo e(\Carbon\Carbon::parse($t['date'])->format('d/m/Y H:i')); ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                    <?php echo e($t['type'] === 'Penjualan' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700'); ?>">
                                    <?php echo e($t['type']); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 font-semibold whitespace-nowrap text-slate-900"><?php echo e($t['code']); ?></td>
                            <td class="py-3 px-4 text-slate-800"><?php echo e($t['customer']); ?></td>
                            <td class="py-3 px-4 text-slate-600"><?php echo e($t['cash_name']); ?></td>
                            <td class="py-3 px-4 text-slate-600"><?php echo e($t['processed_by']); ?></td>
                            <td class="py-3 px-4 text-right tabular-nums font-medium text-slate-900">Rp <?php echo e(number_format($t['total'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-right tabular-nums text-slate-600">Rp <?php echo e(number_format($t['paid'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                    <?php switch($t['status']):
                                        case ('lunas'): ?> bg-emerald-100 text-emerald-700 <?php break; ?>
                                        <?php case ('dp'): ?> bg-amber-100 text-amber-700 <?php break; ?>
                                        <?php case ('COMPLETED'): ?> bg-emerald-100 text-emerald-700 <?php break; ?>
                                        <?php default: ?> bg-slate-100 text-slate-500 <?php break; ?>
                                    <?php endswitch; ?>">
                                    <?php echo e($t['status']); ?>

                                </span>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="9" class="py-10 text-center text-sm text-slate-400">Belum ada transaksi pada periode ini.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/reports/index.blade.php ENDPATH**/ ?>