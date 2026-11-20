<div class="space-y-5">
    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3 sm:items-end">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Laporan Toko</h1>
            <p class="text-xs text-slate-500">Hanya data tokomu • mencakup penjualan tunai & pesanan kacamata selesai</p>
        </div>
        <div class="sm:ml-auto flex gap-3">
            <label class="text-xs">Dari<input type="date" wire:model.live="from" class="mt-1 block rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2"></label>
            <label class="text-xs">Sampai<input type="date" wire:model.live="to" class="mt-1 block rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2"></label>
            <a href="<?php echo e(route('export.sales', ['from' => $from, 'to' => $to])); ?>" class="self-end text-xs font-bold px-4 py-2.5 rounded-lg bg-emerald-500 text-white">⬇ CSV</a>
        </div>
    </div>
    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-xs text-slate-400 uppercase font-semibold">Omzet Penjualan</div>
            <div class="text-xl font-semibold text-emerald-700">Rp <?php echo e(number_format($saleRevenue, 0, ',', '.')); ?></div>
            <div class="text-xs text-slate-400 mt-1"><?php echo e($saleCount); ?> transaksi • retur Rp <?php echo e(number_format($refundTotal, 0, ',', '.')); ?></div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-xs text-slate-400 uppercase font-semibold">Omzet Optical</div>
            <div class="text-xl font-semibold text-blue-600">Rp <?php echo e(number_format($opticalRevenue, 0, ',', '.')); ?></div>
            <div class="text-xs text-slate-400 mt-1"><?php echo e($opticalCount); ?> pesanan</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-xs text-slate-400 uppercase font-semibold">Total Omzet</div>
            <div class="text-xl font-semibold text-slate-900">Rp <?php echo e(number_format($revenue, 0, ',', '.')); ?></div>
            <div class="text-xs text-slate-400 mt-1"><?php echo e($count); ?> transaksi</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-xs text-slate-400 uppercase font-semibold">Kas Bersih</div>
            <div class="text-xl font-semibold">Rp <?php echo e(number_format($collected, 0, ',', '.')); ?></div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-xs text-slate-400 uppercase font-semibold">Piutang</div>
            <div class="text-xl font-semibold text-amber-600">Rp <?php echo e(number_format($receivable, 0, ',', '.')); ?></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Laba / Rugi (Periode)</h2>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="flex justify-between"><span>Omzet</span><span class="font-bold">Rp <?php echo e(number_format($revenue, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between"><span>HPP (COGS)</span><span class="font-bold text-red-600">Rp <?php echo e(number_format($cogs, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between"><span>Laba Kotor</span><span class="font-bold text-emerald-700">Rp <?php echo e(number_format($grossProfit, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between"><span>Pengeluaran</span><span class="font-bold text-red-600">Rp <?php echo e(number_format($expenseTotal, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between col-span-2 border-t pt-2"><span class="font-semibold">Laba Bersih</span><span class="font-bold text-lg <?php echo e($netProfit >= 0 ? 'text-emerald-700' : 'text-red-600'); ?>">Rp <?php echo e(number_format($netProfit, 0, ',', '.')); ?></span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Produk Terlaris</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'top-product-'.e($t->product_id).''; ?>wire:key="top-product-<?php echo e($t->product_id); ?>" class="flex justify-between text-sm py-2 border-b border-slate-50 last:border-0"><span class="font-bold"><?php echo e($t->product?->name ?? '-'); ?> <span class="text-slate-400 font-normal">× <?php echo e($t->qty); ?></span></span><span>Rp <?php echo e(number_format($t->omzet, 0, ',', '.')); ?></span></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p class="text-sm text-slate-400 text-center py-4">Belum ada penjualan pada periode ini.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Metode Pembayaran -->
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Metode Pembayaran</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <?php
                $methodLabels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'card' => 'Kartu'];
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $methodLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'payment-method-'.e($method).''; ?>wire:key="payment-method-<?php echo e($method); ?>" class="p-4 rounded-lg border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-slate-400 uppercase font-semibold"><?php echo e($label); ?></div>
                        <div class="text-lg font-semibold">Rp <?php echo e(number_format($paymentMethods[$method] ?? 0, 0, ',', '.')); ?></div>
                    </div>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center
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
                <div class="p-4 rounded-lg border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-slate-400 uppercase font-semibold">Total</div>
                        <div class="text-lg font-semibold text-slate-900">Rp <?php echo e(number_format(array_sum($paymentMethods), 0, ',', '.')); ?></div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Tren Harian -->
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Tren Harian</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4 text-right">Penjualan</th>
                        <th class="py-3 px-4 text-right">Optical</th>
                        <th class="py-3 px-4 text-right">Total</th>
                        <th class="py-3 px-4 text-center">Jumlah Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $dailyTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'daily-trend-'.e($day['date']).''; ?>wire:key="daily-trend-<?php echo e($day['date']); ?>">
                            <td class="py-3 px-4"><?php echo e(\Carbon\Carbon::parse($day['date'])->format('d M')); ?></td>
                            <td class="py-3 px-4 text-right text-slate-600">Rp <?php echo e(number_format($day['sale'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-right text-blue-600">Rp <?php echo e(number_format($day['optical'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-right font-semibold">Rp <?php echo e(number_format($day['sale'] + $day['optical'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-center"><?php echo e($day['count']); ?></td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-slate-400">Belum ada data pada periode ini.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaksi Terkini -->
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Transaksi Terkini</h2>
            <a href="<?php echo e(route('export.transactions', ['from' => $from, 'to' => $to])); ?>"
               class="text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition">
                ⬇ Export CSV
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Jenis</th>
                        <th class="py-3 px-4 text-left">Kode</th>
                        <th class="py-3 px-4 text-left">Pelanggan</th>
                        <th class="py-3 px-4 text-left">Nama Kas</th>
                        <th class="py-3 px-4 text-left">Diproses Oleh</th>
                        <th class="py-3 px-4 text-right">Total</th>
                        <th class="py-3 px-4 text-right">Dibayar</th>
                        <th class="py-3 px-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'recent-transaction-'.e($t['type']).'-'.e($t['code']).''; ?>wire:key="recent-transaction-<?php echo e($t['type']); ?>-<?php echo e($t['code']); ?>">
                            <td class="py-3 px-4 whitespace-nowrap"><?php echo e(\Carbon\Carbon::parse($t['date'])->format('d/m/Y H:i')); ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                    <?php echo e($t['type'] === 'Penjualan' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700'); ?>">
                                    <?php echo e($t['type']); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium whitespace-nowrap"><?php echo e($t['code']); ?></td>
                            <td class="py-3 px-4"><?php echo e($t['customer']); ?></td>
                            <td class="py-3 px-4 text-slate-600"><?php echo e($t['cash_name']); ?></td>
                            <td class="py-3 px-4 text-slate-600"><?php echo e($t['processed_by']); ?></td>
                            <td class="py-3 px-4 text-right">Rp <?php echo e(number_format($t['total'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-right">Rp <?php echo e(number_format($t['paid'], 0, ',', '.')); ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
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
                            <td colspan="9" class="py-8 text-center text-sm text-slate-400">Belum ada transaksi pada periode ini.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/reports/index.blade.php ENDPATH**/ ?>