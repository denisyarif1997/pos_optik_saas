<div class="grid xl:grid-cols-5 gap-4">
    <div class="xl:col-span-3 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-slate-700 mb-3">Daftar Lab Order</h2>
            <input type="text" wire:model.live="search" placeholder="Cari nomor pesanan atau nama lab..." class="w-full rounded-lg bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300 mb-2">
            <div class="flex gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button wire:click="statusFilter = '<?php echo e($status); ?>'" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium border <?php echo e($statusFilter === $status ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'); ?>"><?php echo e($label); ?></button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <button wire:click="statusFilter = 'all'" class="px-3 py-2 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50">Semua</button>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-2">
            <h2 class="font-semibold text-slate-700 mb-3">Lab Orders</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $labOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 px-3 py-2.5 hover:bg-slate-50 cursor-pointer group" wire:click="selectOrder(<?php echo e($lo->id); ?>)">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold truncate"><?php echo e($lo->order->order_number); ?></div>
                        <div class="text-xs text-slate-500"><?php echo e(optional($lo->order->customer)->name ?? 'Umum'); ?> • <?php echo e($lo->lab_supplier_name); ?></div>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold <?php echo e($lo->status === 'DITERIMA' ? 'bg-emerald-100 text-emerald-700' : ($lo->status === 'SALAH' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')); ?>"><?php echo e(ucfirst(strtolower($lo->status))); ?></span>
                    </div>
                    <div class="text-right text-xs">
                        <div class="text-slate-500"><?php echo e($lo->expected_completion_date ? \Carbon\Carbon::parse($lo->expected_completion_date)->format('d M Y') : '-'); ?></div>
                        <div class="text-slate-400">Rp <?php echo e(number_format($lo->lab_cost, 0, ',', '.')); ?></div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="text-sm text-slate-400 text-center py-6">Belum ada lab order.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php echo e($labOrders->links()); ?>

        </div>
    </div>

    <div class="xl:col-span-2">
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4 xl:sticky xl:top-24">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orderId): ?>
                <div>
                    <h2 class="font-semibold text-slate-700 mb-3">Ubah Status Lab Order</h2>
                    <p class="text-sm text-slate-500 mb-3">Pesanan: <span class="font-bold"><?php echo e($selectedOrder->order->order_number); ?></span></p>
                    <div>
                        <label class="text-sm font-semibold block mb-1.5">Status Baru</label>
                        <select wire:model="newStatus" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($status); ?>"><?php echo e($label); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label class="text-sm font-semibold block mb-1.5">Tanggal Janji (Promised Date)</label>
                        <input type="date" wire:model="promisedDate" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($newStatus === 'SALAH'): ?>
                        <div class="mt-3">
                            <label class="text-sm font-semibold block mb-1.5">Alasan Penolakan</label>
                            <textarea wire:model="rejectionReason" rows="3" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm"></textarea>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button wire:click="updateStatus" <?php if($orderId === null): echo 'disabled'; endif; ?> class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold py-2.5 disabled:opacity-50 disabled:cursor-not-allowed mt-4">Simpan Perubahan</button>
                    <button wire:click="orderId = null" class="w-full text-sm text-slate-500 hover:text-slate-700 mt-2">Batal</button>
                </div>
            <?php else: ?>
                <div class="text-center py-10 text-slate-400">
                    <p class="text-sm">Pilih lab order dari daftar di samping untuk mengubah status.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/livewire/lab/lab-order-tracker.blade.php ENDPATH**/ ?>