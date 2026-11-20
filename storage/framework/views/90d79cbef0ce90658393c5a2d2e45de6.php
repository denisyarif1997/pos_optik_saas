<?php
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $presets = ['today' => 'Hari ini', '7d' => '7 hari', '30d' => '30 hari', 'all' => 'Semua'];
    $badgeClass = fn ($st) => match ($st) {
        'approved' => 'success',
        default => 'info',
    };
?>

<div>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Retur Penjualan','description' => 'Stok kembali dan tercatat']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Retur Penjualan','description' => 'Stok kembali dan tercatat']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('pos.cashier')); ?>" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Transaksi Baru</a>
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
            <p class="text-xs text-emerald-600 font-medium">Retur</p>
            <p class="text-lg font-bold text-emerald-800"><?php echo e(number_format($summary['count'])); ?></p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Total Refund</p>
            <p class="text-lg font-bold text-emerald-800"><?php echo e($rp($summary['refund'])); ?></p>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4 mb-4">
        <h1 class="font-semibold text-text-main">Buat Retur <span class="text-xs font-normal text-text-secondary">• pilih nota, produk, dan qty</span></h1>
        <div class="flex gap-2">
            <input type="text" wire:model="code" placeholder="Kode nota, cth JUAL-..." class="flex-1 rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button wire:click="findSale" class="bg-emerald-500 text-white text-sm font-bold px-4 rounded-lg">Cari</button>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale): ?>
            <div class="rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm">
                <b><?php echo e($sale->code); ?></b> • Rp <?php echo e(number_format($sale->total, 0, ',', '.')); ?>

                <div class="mt-2 space-y-1"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><div>• <?php echo e($it->product?->name); ?> × <?php echo e($it->qty); ?></div><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <select wire:model="product_id" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5 text-sm">
                    <option value="">— Produk —</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><option value="<?php echo e($it->product_id); ?>"><?php echo e($it->product?->name); ?></option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <input type="number" min="1" wire:model="qty" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5" placeholder="Qty">
            </div>
            <input type="text" wire:model="reason" placeholder="Alasan retur" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button wire:click="save" class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">Simpan Retur →</button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3 mb-4">
        <?php if (isset($component)) { $__componentOriginal1c4b45f62348de9b6fa41ee823d3fa96 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1c4b45f62348de9b6fa41ee823d3fa96 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-input','data' => ['model' => 'search','placeholder' => 'Cari kode nota…','label' => 'Cari retur']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'search','placeholder' => 'Cari kode nota…','label' => 'Cari retur']); ?>
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
            <select wire:model.live="status" class="input">
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
                            <th class="py-3 px-4 text-left w-40">Transaksi</th>
                            <th class="py-3 px-4 text-left">Pelanggan</th>
                            <th class="py-3 px-4 text-left">Produk</th>
                            <th class="py-3 px-4 text-right w-24">Qty</th>
                            <th class="py-3 px-4 text-right w-32">Refund</th>
                            <th class="py-3 px-4 text-left">Alasan</th>
                            <th class="py-3 px-4 text-left w-20">Status</th>
                            <th class="py-3 px-4 text-left w-32">Tanggal</th>
                            <th class="py-3 px-4 text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $item = $r->sale->items->first(fn ($i) => $i->product_id === $r->product_id);
                                $productName = $item?->product?->name ?? '-';
                                $badge = $badgeClass($r->status ?? 'approved');
                            ?>
                            <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'return-'.e($r->id).''; ?>wire:key="return-<?php echo e($r->id); ?>" class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-text-main"><?php echo e($r->sale?->code ?? '-'); ?></div>
                                    <p class="text-[11px] text-text-secondary"><?php echo e($r->created_at->translatedFormat('d M Y, H:i')); ?></p>
                                </td>
                                <td class="py-3 px-4 text-[11px] text-text-main"><?php echo e($r->sale->customer?->name ?? '-'); ?></td>
                                <td class="py-3 px-4 text-[11px] text-text-main"><?php echo e($productName); ?></td>
                                <td class="py-3 px-4 text-[11px] text-text-main text-right"><?php echo e($r->qty); ?></td>
                                <td class="py-3 px-4 text-[11px] font-semibold text-text-main text-right"><?php echo e($rp($r->refund_amount)); ?></td>
                                <td class="py-3 px-4 text-[11px] text-text-main"><?php echo e($r->reason ?? '-'); ?></td>
                                <td class="py-3 px-4">
                                    <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => ucfirst($r->status ?? 'approved'),'class' => $badge]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(ucfirst($r->status ?? 'approved')),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($badge)]); ?>
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
                                <td class="py-3 px-4 text-[11px] text-text-secondary"><?php echo e($r->created_at->format('d M Y')); ?></td>
                                <td class="py-3 px-4 text-right">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($r->sale): ?>
                                        <a href="<?php echo e(route('sales.show', $r->sale->id)); ?>" wire:navigate
                                            class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-400 text-sm">Tidak ada retur pada filter ini.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <?php echo e($returns->links()); ?>

    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/returns/index.blade.php ENDPATH**/ ?>