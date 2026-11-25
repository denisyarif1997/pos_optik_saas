<div class="max-w-3xl mx-auto space-y-6">
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Pencatatan Resep Kacamata','description' => 'Catat parameter refraksi mata OD (Kanan) dan OS (Kiri) untuk resep kacamata pelanggan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pencatatan Resep Kacamata','description' => 'Catat parameter refraksi mata OD (Kanan) dan OS (Kiri) untuk resep kacamata pelanggan']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('customers.show', $customer->id)); ?>" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali ke Pelanggan
            </a>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>

    <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl space-y-6">

        <!-- Informasi Pelanggan -->
        <div class="flex items-center gap-3 p-4 bg-canvas rounded-xl border border-line">
            <div class="w-10 h-10 rounded-full bg-emerald text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                <?php echo e(strtoupper(substr($customer->name, 0, 1))); ?>

            </div>
            <div class="min-w-0 flex-1">
                <div class="font-semibold text-text-main text-sm truncate"><?php echo e($customer->name); ?></div>
                <div class="text-xs text-text-secondary mt-0.5">
                    Nomor Kontak: <span class="font-medium text-text-main"><?php echo e($customer->phone ?? 'Tidak ada'); ?></span> &middot;
                    Rekam Medis Resep Baru
                </div>
            </div>
        </div>

        <form wire:submit="save" class="space-y-6">

            <!-- Tanggal Periksa -->
            <div class="max-w-xs">
                <label class="label">Tanggal Pemeriksaan Mata</label>
                <input type="date"
                       wire:model="checked_at"
                       class="input <?php $__errorArgs = ['checked_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['checked_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[11px] text-error mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Grid Refraksi OD & OS -->
            <div class="grid md:grid-cols-2 gap-5">

                <!-- OD (Mata Kanan) -->
                <div class="card p-5 bg-emerald-bg/40 border border-emerald-soft rounded-2xl space-y-3.5">
                    <div class="flex items-center justify-between pb-2.5 border-b border-emerald-soft">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-emerald text-white text-xs font-bold flex items-center justify-center">R</span>
                            <span class="font-bold text-sm text-text-main">OD (Oculus Dexter / Kanan)</span>
                        </div>
                        <span class="text-[11px] font-semibold text-emerald-dark">Mata Kanan</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label text-[10px]">SPH (Sphere)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="od_sph"
                                   placeholder="-1.50 / +0.50"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">CYL (Cylinder)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="od_cyl"
                                   placeholder="-0.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">AXIS (Derajat &deg;)</label>
                            <input type="number"
                                   wire:model="od_axis"
                                   placeholder="0 - 180"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">ADD (Bifokal / Plus Baca)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="od_add"
                                   placeholder="+1.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                    </div>
                </div>

                <!-- OS (Mata Kiri) -->
                <div class="card p-5 bg-sky-50/50 border border-sky-100 rounded-2xl space-y-3.5">
                    <div class="flex items-center justify-between pb-2.5 border-b border-sky-100">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-sky-600 text-white text-xs font-bold flex items-center justify-center">L</span>
                            <span class="font-bold text-sm text-text-main">OS (Oculus Sinister / Kiri)</span>
                        </div>
                        <span class="text-[11px] font-semibold text-sky-700">Mata Kiri</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label text-[10px]">SPH (Sphere)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="os_sph"
                                   placeholder="-1.75 / +0.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">CYL (Cylinder)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="os_cyl"
                                   placeholder="-0.50"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">AXIS (Derajat &deg;)</label>
                            <input type="number"
                                   wire:model="os_axis"
                                   placeholder="0 - 180"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">ADD (Bifokal / Plus Baca)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="os_add"
                                   placeholder="+1.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pupillary Distance & Catatan -->
            <div class="grid sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="label">Pupil Distance / PD (mm)</label>
                    <input type="number"
                           step="0.5"
                           wire:model="pd"
                           placeholder="Contoh: 62 / 64"
                           class="input text-center font-mono font-semibold">
                </div>

                <div class="sm:col-span-2">
                    <label class="label">Catatan Tambahan Optician</label>
                    <input type="text"
                           wire:model="notes"
                           placeholder="Contoh: Lensa anti radiasi blue light, progresif santai"
                           class="input">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-line">
                <a href="<?php echo e(route('customers.show', $customer->id)); ?>" wire:navigate class="btn-ghost px-5 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-7 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'w-4 h-4']); ?>
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
                    <span wire:loading.remove wire:target="save">Simpan Data Resep</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/prescriptions/form.blade.php ENDPATH**/ ?>