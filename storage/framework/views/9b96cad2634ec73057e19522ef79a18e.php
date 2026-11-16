<div class="grid lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h1 class="font-semibold mb-1">Tambah Supplier</h1>
        <p class="text-xs text-slate-500 mb-4">Distributor frame / lab lensa.</p>
        <form wire:submit="save" class="space-y-3">
            <input type="text" wire:model="name" placeholder="Nama supplier" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <input type="text" wire:model="phone" placeholder="HP (opsional)" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">+ Simpan</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Daftar Supplier</h2>
        <div class="grid sm:grid-cols-2 gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex items-center gap-2 rounded-lg border border-emerald-100 px-3 py-2.5 text-sm">
                    <span class="flex-1 min-w-0"><b class="block truncate"><?php echo e($s->name); ?></b><span class="text-xs text-slate-400"><?php echo e($s->phone ?? '-'); ?></span></span>
                    <button wire:click="toggle(<?php echo e($s->id); ?>)" class="text-xs font-bold text-emerald-700"><?php echo e($s->is_active ? 'Aktif' : 'Off'); ?></button>
<a href="<?php echo e(route('suppliers.edit', $s->id)); ?>" wire:navigate class="text-xs font-bold text-slate-500">Ubah</a>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="text-sm text-slate-400">Belum ada supplier.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>


<?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/livewire/suppliers/index.blade.php ENDPATH**/ ?>