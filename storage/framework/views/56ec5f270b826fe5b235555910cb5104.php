
<div class="fixed inset-0 z-50 overflow-y-auto bg-canvas">
<div class="w-full min-h-dvh grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-[1.1fr_1fr]">

    
    <aside class="hidden lg:flex relative overflow-hidden flex-col justify-between gap-10 p-10 xl:p-14 bg-linear-to-br from-emerald to-emerald-dark text-white">
        <div class="wave-blob bg-white/20 w-[420px] h-[420px] -top-24 -left-24"></div>
        <div class="wave-blob bg-white/10 w-[480px] h-[480px] -bottom-40 -right-32"></div>

        <div class="relative z-10 flex items-center gap-3">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-white/15 backdrop-blur ring-1 ring-white/25">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'w-6 h-6']); ?>
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
            </div>
            <span class="text-xl font-bold tracking-tight">OptikGo</span>
        </div>

        <div class="relative z-10 max-w-md">
            <h1 class="text-3xl xl:text-4xl font-bold leading-tight tracking-tight">
                Kelola toko optik Anda dengan lebih cepat dan rapi.
            </h1>
            <p class="mt-4 text-white/80 leading-relaxed">
                Kasir, resep lensa, stok frame, hingga laporan penjualan dalam satu aplikasi.
            </p>

            <ul class="mt-8 space-y-4 text-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                    'Transaksi kasir cepat & akurat',
                    'Stok frame dan lensa terpantau real-time',
                    'Laporan penjualan siap kapan saja',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-6 h-6 shrink-0 rounded-full bg-white/20 ring-1 ring-white/30">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.8 3.8 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        <span class="text-white/90"><?php echo e($feature); ?></span>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>

        <p class="relative z-10 text-xs text-white/60">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'OptikGo')); ?>. All rights reserved.
        </p>
    </aside>

    
    <main class="relative flex items-center justify-center p-6 sm:p-10 lg:p-12 overflow-hidden">
        <div class="wave-blob wave-blob--primary opacity-20 w-[260px] h-[260px] -top-20 -right-20 lg:hidden"></div>

        
        <div class="w-full max-w-md relative z-10">

            
            <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-emerald-bg text-emerald border border-emerald-soft shadow-sm">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'glasses','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'glasses','class' => 'w-6 h-6']); ?>
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
                </div>
                <span class="text-xl font-bold tracking-tight text-text-main">OptikGo</span>
            </div>

            <div class="mb-8 text-center lg:text-left">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-text-main">Selamat datang kembali</h2>
                <p class="mt-2 text-sm text-text-secondary">Masuk untuk membuka kasir tokomu.</p>
            </div>

            <div class="soft-card p-6 sm:p-8">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                    <div class="alert alert-success" role="status">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form wire:submit="login" class="space-y-5" novalidate>

                    
                    <div>
                        <label for="email" class="label">Email</label>
                        <input
                            id="email"
                            type="email"
                            wire:model="email"
                            class="input"
                            placeholder="nama@toko.com"
                            autocomplete="email"
                            autofocus
                            required
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" aria-describedby="email-error" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p id="email-error" class="flex items-center gap-1.5 text-xs text-error mt-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="label mb-0!">Kata sandi</label>
                            <a href="#" class="text-xs font-medium text-text-secondary hover:text-emerald transition-colors">
                                Lupa password?
                            </a>
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                :type="show ? 'text' : 'password'"
                                wire:model="password"
                                class="input pr-11 transition-colors focus-visible:ring-2 focus-visible:ring-emerald/50 focus-visible:outline-none"
                                placeholder="Masukkan kata sandi"
                                autocomplete="current-password"
                                required
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" aria-describedby="password-error" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            >
                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-text-muted hover:text-text-main transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald/50"
                                :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                tabindex="-1"
                            >
                                
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" x-show="!show">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" x-show="show" x-cloak>
                                    <path d="M17.94 17.94A10.9 10.9 0 0112 19c-6.5 0-10-7-10-7a18.5 18.5 0 015.06-5.94M9.9 4.24A9.1 9.1 0 0112 4c6.5 0 10 7 10 7a18.5 18.5 0 01-2.16 3.19M1 1l22 22"/>
                                </svg>
                            </button>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p id="password-error" class="flex items-center gap-1.5 text-xs text-error mt-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <label class="flex items-center gap-2.5 cursor-pointer group w-fit">
                        <input type="checkbox" wire:model="remember"
                               class="w-4 h-4 rounded border-line text-emerald focus:ring-emerald/20 transition-colors cursor-pointer">
                        <span class="text-sm text-text-secondary group-hover:text-text-main transition-colors">Ingat saya di perangkat ini</span>
                    </label>

                    
                    <button
                        type="submit"
                        class="btn-primary w-full py-3 text-[15px] disabled:bg-emerald disabled:opacity-80"
                        wire:loading.attr="disabled"
                        wire:target="login"
                    >
                        <svg wire:loading wire:target="login" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                        <span wire:loading.remove wire:target="login">Masuk</span>
                        <span wire:loading wire:target="login">Memproses…</span>
                    </button>
                </form>
            </div>

            <p class="text-center text-sm text-text-secondary mt-6">
                Belum punya akun?
                <a href="<?php echo e(route('register')); ?>" class="font-semibold text-emerald hover:text-emerald-dark transition-colors" wire:navigate>Daftar sekarang</a>
            </p>

            <p class="lg:hidden text-center mt-8 text-[11px] text-text-muted">
                &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'OptikGo')); ?>. All rights reserved.
            </p>
        </div>
    </main>
</div>
</div><?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/livewire/auth/login.blade.php ENDPATH**/ ?>