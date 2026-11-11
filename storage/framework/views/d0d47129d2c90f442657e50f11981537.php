<div class="grid lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h1 class="font-semibold mb-1">Catat Pengeluaran</h1>
        <p class="text-xs text-slate-500 mb-4">Bulan ini: <b>Rp <?php echo e(number_format($monthTotal, 0, ',', '.')); ?></b></p>
        <form wire:submit="save" class="space-y-3">
            <input type="date" wire:model="spent_at" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <select wire:model="category" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                <option value="operasional">Operasional</option>
                <option value="gaji">Gaji</option>
                <option value="sewa">Sewa</option>
                <option value="listrik">Listrik & Air</option>
                <option value="lainnya">Lainnya</option>
            </select>
            <input type="text" wire:model="description" placeholder="Keterangan" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <input type="number" wire:model="amount" placeholder="Nominal" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">+ Simpan</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Terakhir Dicatat</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="flex items-center gap-3 text-sm py-2 border-b border-slate-50 last:border-0">
                <span class="flex-1"><b><?php echo e($e->description); ?></b> <span class="text-slate-400">• <?php echo e($e->category); ?> • <?php echo e($e->spent_at->format('d M')); ?></span></span>
                <b class="text-red-600">Rp <?php echo e(number_format($e->amount, 0, ',', '.')); ?></b>
                <a href="<?php echo e(route('expenses.edit', $e->id)); ?>" wire:navigate class="text-xs font-bold text-slate-400">Ubah</a>
<button wire:click="delete(<?php echo e($e->id); ?>)" wire:confirm="Hapus?" class="text-xs text-slate-400">✕</button>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p class="text-sm text-slate-400 text-center py-4">Belum ada pengeluaran.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/expenses/index.blade.php ENDPATH**/ ?>