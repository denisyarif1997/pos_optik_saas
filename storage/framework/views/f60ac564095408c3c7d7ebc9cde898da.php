<div class="space-y-5"
     x-data="{
         seriesVisible: {
             lenses: true,
             frames: true,
             returns: true
         },
         hoveredIndex: null,
         toggleSeries(key) {
             this.seriesVisible[key] = !this.seriesVisible[key];
         }
     }">

    <!-- ================= SECTION HEADER & COMPACT FILTERS ================= -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold tracking-tight text-slate-900">Ringkasan Operasional Optik</h2>
            <p class="text-xs text-slate-500 mt-0.5 tabular-nums">
                Diperbarui: <?php echo e(now()->translatedFormat('d M Y, H:i')); ?> WIB &bull; <?php echo e($store->name); ?>

            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="period" aria-label="Filter Periode"
                    class="h-9 rounded-lg border border-[#e2e8f0] bg-white px-3 text-xs font-medium text-slate-700 focus:border-[#10b981] focus:outline-none">
                <option value="7d">7 Hari Terakhir</option>
                <option value="30d">30 Hari Terakhir</option>
                <option value="month">Bulan Berjalan</option>
                <option value="year">Tahun Ini</option>
            </select>

            <select wire:model.live="selectedStoreId" aria-label="Filter Cabang / Outlet"
                    class="h-9 rounded-lg border border-[#e2e8f0] bg-white px-3 text-xs font-medium text-slate-700 focus:border-[#10b981] focus:outline-none">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <option value="<?php echo e($st->id); ?>"><?php echo e($st->name); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>

            <a href="<?php echo e(route('pos.cashier')); ?>"
               class="h-9 inline-flex items-center gap-1.5 rounded-lg bg-[#10b981] hover:bg-[#0d9488] px-3.5 text-xs font-semibold text-white transition-colors">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'plus','class' => 'w-3.5 h-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus','class' => 'w-3.5 h-3.5']); ?>
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
        </div>
    </div>

    <!-- ================= TOP KPI SUMMARY ROW (4 EQUAL-WIDTH CARDS) ================= -->
    <section aria-label="Ringkasan KPI Optik" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <!-- KPI 1: Frame Ready vs Stok -->
        <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Frame Siap Jual</p>
                    <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 tabular-nums"><?php echo e(number_format($readyFrameCount)); ?></p>
                </div>
                <span class="w-9 h-9 rounded-lg bg-emerald-50 text-[#10b981] flex items-center justify-center flex-shrink-0">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'w-4 h-4']); ?>
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
            <div class="mt-3.5">
                <?php
                    $frameShare = min(100, $readyFrameCount > 0 ? round(($readyFrameCount / max(1, $totalActiveProducts)) * 100) : 0);
                ?>
                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                    <span>Frame siap jual vs pesanan lab</span>
                    <span class="font-semibold text-[#0d9488] tabular-nums"><?php echo e($frameShare); ?>% ready</span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-amber-100 overflow-hidden">
                    <div class="h-full rounded-full bg-[#10b981]" style="width: <?php echo e($frameShare); ?>%"></div>
                </div>
            </div>
        </article>

        <!-- KPI 3: Ketersediaan Stok Lensa -->
        <article class="bg-white rounded-[10px] border <?php echo e($lowStock > 0 ? 'border-amber-300' : 'border-[#e2e8f0]'); ?> p-4 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Ketersediaan Stok Lensa</p>
                    <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 tabular-nums"><?php echo e(number_format($totalLensStock)); ?></p>
                </div>
                <span class="w-9 h-9 rounded-lg bg-amber-50 text-[#f59e0b] flex items-center justify-center flex-shrink-0">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'layers','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'layers','class' => 'w-4 h-4']); ?>
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
            <div class="mt-3.5">
                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                    <span><?php echo e($lowStock > 0 ? $lowStock . ' produk stok menipis' : 'Semua stok lensa aman'); ?></span>
                    <a href="<?php echo e(route('inventory.index')); ?>" class="font-semibold text-[#0d9488] hover:underline">Cek Stok</a>
                </div>
                <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full <?php echo e($lowStock > 0 ? 'bg-[#f59e0b]' : 'bg-[#10b981]'); ?>" style="width: <?php echo e($totalActiveProducts > 0 ? max(15, 100 - round(($lowStock / $totalActiveProducts) * 100)) : 100); ?>%"></div>
                </div>
            </div>
        </article>

        <!-- KPI 4: Omzet Periode & Piutang -->
        <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Omzet Penjualan</p>
                    <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 tabular-nums truncate">Rp <?php echo e(number_format($periodRevenue, 0, ',', '.')); ?></p>
                </div>
                <span class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chart','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chart','class' => 'w-4 h-4']); ?>
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
            <div class="mt-3.5">
                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                    <span>Hari ini: Rp <?php echo e(number_format($todaySales, 0, ',', '.')); ?></span>
                    <span class="font-semibold tabular-nums <?php echo e($growth >= 0 ? 'text-[#10b981]' : 'text-[#ef4444]'); ?>">
                        <?php echo e($growth >= 0 ? '+' : ''); ?><?php echo e($growth); ?>%
                    </span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-sky-500" style="width: <?php echo e($periodRevenue > 0 ? 78 : 10); ?>%"></div>
                </div>
            </div>
        </article>
    </section>

    <!-- ================= BOTTOM MAIN ANALYTICS PANEL ================= -->
    <section class="bg-white rounded-[10px] border border-[#e2e8f0] p-5">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="w-1 h-5 rounded-full bg-[#10b981]"></span>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Distribusi Penjualan &amp; Pesanan Optik</h3>
                    <p class="text-[11px] text-slate-500">Perbandingan volume lensa, frame, pesanan lab, dan retur</p>
                </div>
            </div>

            <a href="<?php echo e(route('export.sales', ['from' => $rangeFrom, 'to' => $rangeTo])); ?>"
               title="Unduh Laporan CSV"
               class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg border border-[#e2e8f0] bg-white hover:bg-slate-50 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
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
                <span class="hidden sm:inline">Export</span>
            </a>
        </div>

        <!-- Interactive Color-Coded Legend Grouped Left & Right -->
        <div class="flex flex-wrap items-center justify-between gap-3 py-3 text-xs">
            <div class="flex flex-wrap items-center gap-4">
                <button type="button" @click="toggleSeries('lenses')"
                        :class="seriesVisible.lenses ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3b82f6]"></span>
                    Penjualan Lensa
                </button>
                <button type="button" @click="toggleSeries('frames')"
                        :class="seriesVisible.frames ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                    Frame &amp; Aksesori
                </button>
                <button type="button" @click="toggleSeries('orders')"
                        :class="seriesVisible.orders ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#f59e0b]"></span>
                    Pesanan Optik
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <button type="button" @click="toggleSeries('returns')"
                        :class="seriesVisible.returns ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span>
                    Retur Produk
                </button>
                <span class="inline-flex items-center gap-1.5 text-slate-500">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-300"></span>
                    Piutang: <strong class="text-slate-700 tabular-nums">Rp <?php echo e(number_format($unpaid, 0, ',', '.')); ?></strong>
                </span>
            </div>
        </div>

        <!-- Multi-Column Grouped Bar Chart -->
        <div class="relative mt-2 pt-4">
            <!-- Clean Horizontal Gridlines -->
            <div class="relative h-56 flex items-end gap-2 sm:gap-3 border-b border-[#e2e8f0] pb-1">
                <div aria-hidden="true" class="pointer-events-none absolute inset-0 flex flex-col justify-between">
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div></div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $chartSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $hLens = $maxChartVal > 0 ? max($item['lenses'] > 0 ? 8 : 2, round(($item['lenses'] / $maxChartVal) * 100)) : 2;
                        $hFrame = $maxChartVal > 0 ? max($item['frames'] > 0 ? 8 : 2, round(($item['frames'] / $maxChartVal) * 100)) : 2;
                        $hOrder = $maxChartVal > 0 ? max($item['orders'] > 0 ? 8 : 2, round(($item['orders'] / $maxChartVal) * 100)) : 2;
                        $hRet = $maxChartVal > 0 ? max($item['returns'] > 0 ? 8 : 2, round(($item['returns'] / $maxChartVal) * 100)) : 2;
                    ?>
                    <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'chart-col-'.e($idx).''; ?>wire:key="chart-col-<?php echo e($idx); ?>"
                         @mouseenter="hoveredIndex = <?php echo e($idx); ?>"
                         @mouseleave="hoveredIndex = null"
                         class="relative z-10 flex-1 min-w-0 h-full flex flex-col items-center justify-end group">

                        <!-- Hover Tooltip -->
                        <div x-show="hoveredIndex === <?php echo e($idx); ?>" x-cloak
                             class="absolute -top-2 left-1/2 -translate-x-1/2 -translate-y-full bg-slate-900 text-white rounded-md px-2.5 py-1.5 text-[10px] whitespace-nowrap shadow-lg z-20 pointer-events-none tabular-nums">
                            <div class="font-bold border-b border-white/15 pb-0.5 mb-1"><?php echo e($item['label']); ?></div>
                            <div>Lensa: <?php echo e($item['lenses']); ?> &bull; Frame: <?php echo e($item['frames']); ?></div>
                            <div>Order: <?php echo e($item['orders']); ?> &bull; Retur: <?php echo e($item['returns']); ?></div>
                        </div>

                        <div class="w-full h-full flex items-end justify-center gap-1 px-0.5 sm:px-1.5">
                            <div x-show="seriesVisible.lenses"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#3b82f6] transition-all duration-200 group-hover:opacity-90"
                                 style="height: <?php echo e($hLens); ?>%"></div>
                            <div x-show="seriesVisible.frames"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#10b981] transition-all duration-200 group-hover:opacity-90"
                                 style="height: <?php echo e($hFrame); ?>%"></div>
                            <div x-show="seriesVisible.orders"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#f59e0b] transition-all duration-200 group-hover:opacity-90"
                                 style="height: <?php echo e($hOrder); ?>%"></div>
                            <div x-show="seriesVisible.returns"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#ef4444] transition-all duration-200 group-hover:opacity-90"
                                 style="height: <?php echo e($hRet); ?>%"></div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <!-- Category Labels Below Axis -->
            <div class="flex items-center gap-2 sm:gap-3 pt-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $chartSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="flex-1 min-w-0 text-center text-[11px] font-medium text-slate-500 truncate tabular-nums">
                        <?php echo e($item['label']); ?>

                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ================= OPERATIONAL DETAIL PANELS ================= -->
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <!-- Transaksi Terbaru (2 cols) -->
        <article class="xl:col-span-2 bg-white rounded-[10px] border border-[#e2e8f0] overflow-hidden">
            <div class="flex items-center justify-between gap-3 px-4 py-3.5 border-b border-[#e2e8f0]">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-[#3b82f6]"></span>
                    <h3 class="text-sm font-bold text-slate-900">Transaksi Terbaru</h3>
                </div>
                <a href="<?php echo e(route('sales.index')); ?>" class="text-xs font-semibold text-[#0d9488] hover:underline">
                    Semua Transaksi &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-[#f4f7f9] border-b border-[#e2e8f0] text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Nota</th>
                            <th class="px-4 py-2.5 text-left">Waktu</th>
                            <th class="px-4 py-2.5 text-right">Total Bersih</th>
                            <th class="px-4 py-2.5 text-left">Status</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'dashboard-sale-'.e($s->id).''; ?>wire:key="dashboard-sale-<?php echo e($s->id); ?>" class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-4 py-2.5 font-semibold text-slate-900"><?php echo e($s->code); ?></td>
                                <td class="px-4 py-2.5 text-slate-500 tabular-nums whitespace-nowrap"><?php echo e($s->sold_at->translatedFormat('d M, H:i')); ?></td>
                                <td class="px-4 py-2.5 text-right font-semibold text-slate-900 tabular-nums whitespace-nowrap">Rp <?php echo e(number_format($s->net_total, 0, ',', '.')); ?></td>
                                <td class="px-4 py-2.5">
                                    <div class="flex flex-wrap items-center gap-1">
                                        <span class="status-badge <?php echo e($s->payment_status === 'lunas' ? 'success' : ($s->payment_status === 'dp' ? 'warning' : 'error')); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $s->payment_status))); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($s->return_status): ?>
                                            <span class="status-badge <?php echo e($s->return_status === 'Diretur' ? 'error' : 'warning'); ?>"><?php echo e($s->return_status); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <a href="<?php echo e(route('sales.show', $s->id)); ?>" wire:navigate
                                       class="inline-flex items-center px-2.5 py-1 rounded border border-[#e2e8f0] text-[11px] font-semibold text-slate-600 hover:bg-emerald-50 hover:text-[#0d9488] hover:border-emerald-200 transition-colors">
                                        Buka
                                    </a>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada transaksi tercatat.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>

        <!-- Siap Diambil & Stok Menipis (1 col) -->
        <div class="space-y-4">
            <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Pesanan Siap Diambil</h3>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-[#0d9488] text-[11px] font-bold tabular-nums"><?php echo e($readyOrders->count()); ?></span>
                </div>
                <div class="divide-y divide-slate-100 mt-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $readyOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route('optical-orders.index')); ?>" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'ready-order-'.e($o->id).''; ?>wire:key="ready-order-<?php echo e($o->id); ?>" class="flex items-center justify-between gap-2 py-2.5 first:pt-1 last:pb-0">
                            <div class="min-w-0">
                                <span class="block text-xs font-semibold text-slate-800 truncate"><?php echo e($o->code); ?></span>
                                <span class="block text-[11px] text-slate-500 truncate"><?php echo e($o->customer?->name ?? 'Pelanggan umum'); ?></span>
                            </div>
                            <span class="status-badge success">Siap</span>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <p class="py-4 text-center text-xs text-slate-400">Belum ada pesanan siap diambil.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </article>

            <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Stok Lensa/Produk Menipis</h3>
                    <a href="<?php echo e(route('inventory.index')); ?>" class="text-[11px] font-semibold text-[#0d9488] hover:underline">Inventori &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100 mt-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'low-stock-'.e($p->id).''; ?>wire:key="low-stock-<?php echo e($p->id); ?>" class="flex items-center justify-between gap-2 py-2.5 first:pt-1 last:pb-0">
                            <div class="min-w-0">
                                <span class="block text-xs font-semibold text-slate-800 truncate"><?php echo e($p->name); ?></span>
                                <span class="block text-[11px] text-slate-400">Min. <?php echo e($p->min_stock); ?> unit</span>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[11px] font-bold tabular-nums"><?php echo e($p->stock); ?> sisa</span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <p class="py-4 text-center text-xs text-emerald-700 font-medium">Semua stok berada di atas batas minimum.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </article>
        </div>
    </section>
</div>
<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/dashboard/index.blade.php ENDPATH**/ ?>