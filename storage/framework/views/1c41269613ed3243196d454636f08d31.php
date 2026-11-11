<div class="space-y-5">
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Inventori Lensa Stok</h1>
            <p class="text-xs text-slate-500">Kelola matriks stok lensa per SPH × CYL untuk produk tipe lensa stok.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3">
        <input type="text" wire:model.live="search" placeholder="Cari nama atau SKU lensa stok..." class="flex-1 rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
    </div>

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="min-w-0 flex-1">
                        <div class="font-semibold truncate"><?php echo e($p->name); ?></div>
                        <div class="text-xs text-slate-400"><?php echo e($p->sku); ?> • Lensa Stok</div>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                </div>
                <div class="mt-3 text-sm font-semibold text-emerald-700">Rp <?php echo e(number_format($p->sell_price, 0, ',', '.')); ?></div>
                <div class="mt-5 flex gap-2">
                    <a href="<?php echo e(route('inventory.lens-grid', $p->id)); ?>" wire:navigate class="flex-1 text-center px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-sm font-bold">Buka Grid Stok</a>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-xl border border-dashed border-emerald-200 p-10 text-center text-sm text-slate-400">Belum ada produk lensa stok. Tambahkan produk dengan tipe "Lensa Stok" terlebih dahulu.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php echo e($products->links()); ?>

</div>
<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/inventory/index.blade.php ENDPATH**/ ?>