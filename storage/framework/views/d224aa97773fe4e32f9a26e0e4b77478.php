<div class="grid xl:grid-cols-3 gap-4">
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-xl font-semibold">Nota <?php echo e($sale->code); ?></h1>
                <p class="text-xs text-slate-500"><?php echo e($sale->sold_at->format('d M Y H:i')); ?> • <?php echo e($sale->customer?->name ?? 'Umum'); ?></p>
            </div>
            <span class="ml-auto text-[11px] font-bold px-3 py-1.5 rounded-full <?php echo e($sale->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'); ?>"><?php echo e($sale->payment_status); ?></span>
            <a href="<?php echo e(route('print.sale', $sale->id)); ?>" target="_blank" class="text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-500 text-white">🖨️ Cetak</a>
            <a href="<?php echo e(route('sales.index')); ?>" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </div>
        <div class="mt-4 space-y-2 text-sm">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex justify-between gap-3 py-2 border-b border-slate-50 last:border-0">
                    <span><?php echo e($it->product?->name); ?> <span class="text-slate-400">× <?php echo e($it->qty); ?></span></span>
                    <b>Rp <?php echo e(number_format($it->subtotal, 0, ',', '.')); ?></b>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <div class="mt-4 rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm space-y-1">
            <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>Rp <?php echo e(number_format($sale->subtotal, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between font-semibold text-lg"><span>Total</span><span>Rp <?php echo e(number_format($sale->total, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between text-emerald-700 font-bold"><span>Dibayar</span><span>Rp <?php echo e(number_format($sale->paid_total, 0, ',', '.')); ?></span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <h2 class="font-semibold">Pembayaran</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sale->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="flex justify-between text-sm py-2 border-b border-slate-50 last:border-0"><span><?php echo e(strtoupper($p->method)); ?> • <?php echo e($p->paid_at->format('d M H:i')); ?></span><b>Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?></b></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p class="text-sm text-slate-400">Belum ada pembayaran.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->payment_status !== 'lunas'): ?>
            <form wire:submit="addPayment" class="space-y-3 pt-2">
                <div class="grid grid-cols-2 gap-3">
                    <select wire:model="method" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5 text-sm">
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                    <input type="number" wire:model="amount" placeholder="Nominal" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5">
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">+ Catat Pelunasan</button>
            </form>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/sales/show.blade.php ENDPATH**/ ?>