<div class="grid xl:grid-cols-3 gap-4">
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 p-6 space-y-4">
      <div class="flex flex-wrap items-start justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold">Pesanan <?php echo e($order->code); ?></h1>
        <p class="text-sm text-slate-500"><?php echo e($order->customer?->name ?? '-'); ?> • Lab <?php echo e($order->lab_name ?? '-'); ?></p>
    </div>

    <a href="<?php echo e(route('optical-orders.index')); ?>" wire:navigate
       class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5 shrink-0">
        <span>&larr;</span> Kembali
    </a>
</div>
        
        <div class="space-y-2 text-sm">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex justify-between py-2 border-b border-slate-50 last:border-0"><span><?php echo e($it->description); ?> × <?php echo e($it->qty); ?></span><b>Rp <?php echo e(number_format($it->subtotal, 0, ',', '.')); ?></b></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <div class="rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->subtotal > 0): ?>
                <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></span></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount_total > 0): ?>
                    <div class="flex justify-between text-slate-500"><span>Diskon (<?php echo e(number_format($order->discount_percent, 2, ',', '.')); ?>%)</span><span>- Rp <?php echo e(number_format($order->discount_total, 0, ',', '.')); ?></span></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->ppn_amount > 0): ?>
                    <div class="flex justify-between text-slate-500"><span>PPN (<?php echo e(number_format($order->ppn_rate, 0)); ?>%)</span><span>+ Rp <?php echo e(number_format($order->ppn_amount, 0, ',', '.')); ?></span></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="flex justify-between font-semibold"><span>Total</span><span>Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between text-emerald-700 font-bold"><span>Sudah Bayar (DP)</span><span>Rp <?php echo e(number_format($order->dp_total, 0, ',', '.')); ?></span></div>
            <div class="flex justify-between text-red-600 font-bold"><span>Sisa / Piutang</span><span>Rp <?php echo e(number_format($order->receivable, 0, ',', '.')); ?></span></div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold mb-3">Riwayat Pembayaran</h2>
            <div class="space-y-2 text-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $order->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="flex justify-between gap-3 py-1.5 border-b border-slate-50 last:border-0">
                        <span><?php echo e($p->paid_at->format('d/m/Y H:i')); ?> • <?php echo e(strtoupper($p->method)); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->receiver): ?> (<?php echo e($p->receiver->name); ?>) <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><span class="block text-xs text-slate-500"><?php echo e($p->cashAccount?->name ?? 'Akun lama tidak tercatat'); ?></span></span>
                        <b>Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?></b>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <p class="text-sm text-slate-400">Belum ada pembayaran.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold mb-3">Status Pengerjaan</h2>
            <form wire:submit="updateStatus" class="space-y-3">
                <select wire:model="status" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                    <option value="DRAFT">Baru</option>
                    <option value="DIPROSES">Diproses</option>
                    <option value="SIAP">Siap diambil</option>
                    <option value="COMPLETED">Selesai</option>
                    <option value="BATAL">Dibatalkan</option>
                </select>
                <button
                    wire:loading.attr="disabled"
                    class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold flex items-center justify-center gap-2"
                >
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'spinner','class' => 'w-4 h-4 spin','wire:loading.remove' => true,'wire:target' => 'updateStatus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'spinner','class' => 'w-4 h-4 spin','wire:loading.remove' => true,'wire:target' => 'updateStatus']); ?>
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
                    <span wire:loading.remove wire:target="updateStatus">Perbarui Status</span>
                    <span wire:loading wire:target="updateStatus">Memproses...</span>
                </button>
            </form>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->statusMessage): ?>
                <div class="mt-3 alert alert-success">
                    <?php echo e($this->statusMessage); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canReceivePayment && $order->receivable > 0): ?>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold mb-3">Catat Pembayaran (Cicilan)</h2>
            <form wire:submit="addPayment" class="space-y-3">
                <div>
                    <input type="number" step="0.01" wire:model="payAmount" placeholder="Jumlah (sisa Rp <?php echo e(number_format($order->receivable, 0, ',', '.')); ?>)" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <select wire:model="payMethod" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="card">Kartu</option>
                    </select>
                </div>
                <div>
                    <select wire:model="cashAccountId" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                        <option value="">Pilih akun kas</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cashAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($account->id); ?>"><?php echo e($account->name); ?> (<?php echo e($account->type === 'cash' ? 'Tunai' : 'Bank'); ?>)</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cashAccountId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <input type="text" wire:model="payNotes" placeholder="Catatan (opsional)" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                </div>
                <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold">Simpan Pembayaran</button>
            </form>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div>
            <a href="<?php echo e(route('print.optical-order', $order->id)); ?>" target="_blank" class="block w-full text-center bg-slate-900 hover:bg-black text-white text-sm font-semibold py-2 rounded-lg">Cetak Nota Pesanan</a>
        </div>
    </div>
</div><?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/livewire/optical-orders/show.blade.php ENDPATH**/ ?>