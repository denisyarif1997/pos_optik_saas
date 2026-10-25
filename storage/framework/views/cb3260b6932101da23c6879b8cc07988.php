<div class="space-y-5">
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Pesanan Kacamata</h1>
            <p class="text-xs text-slate-500">Lacak pengerjaan sampai diambil</p>
        </div>
        <a href="<?php echo e(route('optical-orders.receivables')); ?>" wire:navigate class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg">Piutang</a>
<a href="<?php echo e(route('optical-orders.create')); ?>" wire:navigate class="ml-auto bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg ">+ Pesanan Baru</a>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <select wire:model.live="status" class="w-full sm:w-64 rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <option value="">Semua status</option>
            <option value="baru">Baru</option>
            <option value="diproses">Diproses</option>
            <option value="menunggu_lensa">Menunggu lensa</option>
            <option value="siap_diambil">Siap diambil</option>
            <option value="diambil">Diambil</option>
            <option value="selesai">Selesai</option>
            <option value="dibatalkan">Dibatalkan</option>
        </select>
    </div>
    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a href="<?php echo e(route('optical-orders.show', $o->id)); ?>" wire:navigate class="bg-white rounded-xl border border-slate-200 p-5 hover:border-emerald-300">
                <div class="flex items-center gap-2"><span class="font-semibold"><?php echo e($o->code); ?></span><span class="ml-auto text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700"><?php echo e($o->status); ?></span></div>
                <div class="text-sm text-slate-500 mt-1"><?php echo e($o->customer?->name ?? '-'); ?></div>
                <div class="text-sm font-semibold mt-2">Rp <?php echo e(number_format($o->total, 0, ',', '.')); ?> <span class="text-xs font-normal text-slate-400">• DP Rp <?php echo e(number_format($o->dp_total, 0, ',', '.')); ?></span></div>
            </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-xl border border-dashed border-emerald-200 p-10 text-center text-sm text-slate-400">Belum ada pesanan.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php echo e($orders->links()); ?>

</div>


<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/optical-orders/index.blade.php ENDPATH**/ ?>