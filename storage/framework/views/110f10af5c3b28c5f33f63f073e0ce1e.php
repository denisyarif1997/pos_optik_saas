<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-xl font-semibold tracking-tight">Penyesuaian Stok</h1>
        <p class="text-sm text-slate-500 mb-5"><?php echo e($product->name); ?> • stok sekarang <b><?php echo e($product->stock); ?></b></p>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-semibold">Jumlah (+ masuk / − keluar)</label>
                <input type="number" wire:model="qty" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 outline-none focus:ring-2 focus:ring-emerald-300" placeholder="cth: 10 atau -2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="text-sm font-semibold">Catatan</label>
                <input type="text" wire:model="notes" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5" placeholder="Stok opname / koreksi">
            </div>
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold ">Simpan & Catat →</button>
        </form>
    </div>
</div>


<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/products/adjust.blade.php ENDPATH**/ ?>