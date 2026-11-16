<div class="mx-auto max-w-5xl space-y-6">
    <div>
        <h1 class="text-xl font-bold text-text-main">Master Akun Kas</h1>
        <p class="mt-1 text-sm text-text-secondary">Kelola akun tujuan pembayaran. Halaman ini tidak mencatat saldo atau mutasi.</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusMessage): ?>
        <div class="alert <?php echo e($statusType === 'error' ? 'alert-error' : 'alert-success'); ?>"><?php echo e($statusMessage); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section class="soft-card p-5">
        <h2 class="font-semibold text-text-main"><?php echo e($cashAccountId ? 'Edit akun kas' : 'Tambah akun kas'); ?></h2>
        <form wire:submit="save" class="mt-4 grid gap-4 sm:grid-cols-[1fr_200px_auto] sm:items-end">
            <div>
                <label for="cashAccountName" class="label">Nama akun</label>
                <input id="cashAccountName" type="text" wire:model="name" class="input" placeholder="Contoh: Kas Toko">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label for="cashAccountType" class="label">Jenis akun</label>
                <select id="cashAccountType" wire:model="type" class="input">
                    <option value="cash">Tunai</option>
                    <option value="bank">Bank</option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary"><?php echo e($cashAccountId ? 'Simpan' : 'Tambah'); ?></button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cashAccountId): ?>
                    <button type="button" wire:click="cancelEditing" class="btn-ghost">Batal</button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </form>
    </section>

    <section class="soft-card overflow-hidden">
        <div class="border-b border-line px-5 py-4">
            <h2 class="font-semibold text-text-main">Daftar akun kas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="bg-canvas text-xs text-text-secondary">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Nama akun</th>
                        <th class="px-5 py-3 font-semibold">Jenis</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'cash-account-'.e($account->id).''; ?>wire:key="cash-account-<?php echo e($account->id); ?>">
                            <td class="px-5 py-3 font-medium text-text-main"><?php echo e($account->name); ?></td>
                            <td class="px-5 py-3 text-text-secondary"><?php echo e($account->type === 'cash' ? 'Tunai' : 'Bank'); ?></td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold <?php echo e($account->is_active ? 'bg-emerald-bg text-emerald-dark' : 'bg-canvas text-text-muted'); ?>">
                                    <?php echo e($account->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit(<?php echo e($account->id); ?>)" class="btn-ghost px-3 py-1.5 text-xs">Edit</button>
                                    <button type="button" wire:click="toggleActive(<?php echo e($account->id); ?>)" class="btn-ghost px-3 py-1.5 text-xs">
                                        <?php echo e($account->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/livewire/cash-accounts/index.blade.php ENDPATH**/ ?>