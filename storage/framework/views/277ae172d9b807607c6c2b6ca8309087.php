<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-xl font-semibold tracking-tight mb-5"><?php echo e($orderId ? 'Ubah Pesanan' : 'Pesanan Kacamata Baru'); ?></h1>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-semibold">Pelanggan</label>
                <select wire:model.live="customer_id" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                    <option value="">— Pilih —</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Lab / supplier lensa</label>
                    <input type="text" wire:model="lab_name" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
                <div>
                    <label class="text-sm font-semibold">Estimasi selesai</label>
                    <input type="date" wire:model="estimated_done_at" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
                <div>
                    <label class="text-sm font-semibold">Total harga</label>
                    <input type="number" wire:model="total" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
                <div>
                    <label class="text-sm font-semibold">DP</label>
                    <input type="number" wire:model="dp_total" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$orderId): ?>
                <div>
                    <label class="text-sm font-semibold">Frame dari stok (opsional)</label>
                    <div class="mt-2 grid sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $frames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <label class="flex items-center gap-2 text-sm rounded-lg border border-emerald-100 px-3 py-2"><input type="checkbox" wire:model="frameIds" value="<?php echo e($f->id); ?>" class="accent-emerald-600"><span class="truncate"><?php echo e($f->name); ?></span></label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold ">Simpan Pesanan →</button>
        </form>
    </div>
</div>


<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/optical-orders/form.blade.php ENDPATH**/ ?>