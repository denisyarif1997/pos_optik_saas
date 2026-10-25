<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Stock Opname</h1>
        <button wire:click="create" class="bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">+ Mulai Opname Baru</button>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left table-head">
                    <th class="py-2 px-3">Kode</th>
                    <th class="px-3">Tanggal</th>
                    <th class="px-3">Status</th>
                    <th class="px-3">Oleh</th>
                    <th class="px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $opnames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="py-2 px-3 font-bold"><?php echo e($o->code); ?></td>
                        <td class="px-3"><?php echo e($o->conducted_at->format('d/m/Y')); ?></td>
                        <td class="px-3">
                            <span class="text-xs px-2 py-0.5 rounded-full <?php echo e($o->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($o->status === 'cancelled' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700')); ?>"><?php echo e($o->status); ?></span>
                        </td>
                        <td class="px-3"><?php echo e($o->conductor?->name ?? '-'); ?></td>
                        <td class="px-3 text-right">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($o->status === 'draft'): ?>
                                <a href="<?php echo e(route('stock-opnames.count', $o->id)); ?>" wire:navigate class="text-xs font-bold text-emerald-700">Hitung</a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="5" class="py-6 text-center text-sm text-slate-400">Belum ada opname.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/stock-opnames/index.blade.php ENDPATH**/ ?>