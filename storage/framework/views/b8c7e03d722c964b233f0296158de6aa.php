<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - <?php echo e(config('app.name', 'OptikGo')); ?></title>
    <link rel="icon" type="image/svg+xml" href="/images/logo.svg"/>
    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
    <link rel="apple-touch-icon" href="/images/logo.svg"/>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="min-h-screen antialiased bg-canvas flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-surface border border-line shadow-card rounded-2xl p-8 sm:p-10 text-center">
            <div class="w-12 h-12 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center mx-auto mb-6">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mail','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mail','class' => 'w-6 h-6']); ?>
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

            <h2 class="text-xl font-semibold text-text-main mb-2">Verifikasi Email</h2>
            <p class="text-text-secondary text-sm mb-6">
                Kami telah mengirimkan tautan verifikasi ke alamat email Anda.
                Silakan cek inbox dan klik tautan tersebut untuk mengaktivasi akun.
            </p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                <div class="mb-6 p-3 rounded-lg bg-emerald-bg text-emerald-dark text-xs font-medium">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('verification.resend')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="btn-ghost w-full py-2 text-sm font-medium text-text-main hover:text-emerald transition-colors">
                    Kirim ulang tautan verifikasi
                </button>
            </form>

            <p class="mt-6 text-xs text-text-muted">
                Tidak menerima email?
                <a href="<?php echo e(route('verification.resend')); ?>" class="font-semibold text-emerald hover:text-emerald-dark">
                    Minta kirim ulang
                </a>
            </p>
        </div>
    </div>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html><?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/livewire/auth/verify-notice.blade.php ENDPATH**/ ?>