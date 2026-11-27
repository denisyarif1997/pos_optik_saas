
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'paginator',
    'onEachSide' => 2,
    'livewire' => true,
    'pageName' => 'page',
    'showInfo' => true,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'paginator',
    'onEachSide' => 2,
    'livewire' => true,
    'pageName' => 'page',
    'showInfo' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasPages() || $showInfo): ?>
    <?php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        // Susun daftar halaman: 1 ... (current-n .. current+n) ... last
        $pages = collect([1, $last])
            ->merge(range(max(1, $current - $onEachSide), min($last, $current + $onEachSide)))
            ->unique()
            ->sort()
            ->values();

        $items = [];
        $prev = null;
        foreach ($pages as $p) {
            if ($prev !== null && $p - $prev > 1) {
                $items[] = '...';
            }
            $items[] = $p;
            $prev = $p;
        }

        $base = 'inline-flex items-center justify-center min-w-[2.25rem] h-9 px-3 text-sm font-medium rounded-lg border transition';
        $idle = 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:text-emerald-600 hover:border-emerald-300';
        $active = 'bg-emerald-600 text-white border-emerald-600 shadow-sm cursor-default';
        $disabled = 'bg-gray-50 text-gray-300 border-gray-200 cursor-not-allowed';
    ?>

    <nav role="navigation" aria-label="Pagination"
         class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between">

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showInfo): ?>
            <p class="text-sm text-gray-500">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->total() > 0): ?>
                    Menampilkan
                    <span class="font-semibold text-gray-700"><?php echo e($paginator->firstItem()); ?></span>
                    -
                    <span class="font-semibold text-gray-700"><?php echo e($paginator->lastItem()); ?></span>
                    dari
                    <span class="font-semibold text-gray-700"><?php echo e(number_format($paginator->total(), 0, ',', '.')); ?></span>
                    data
                <?php else: ?>
                    Tidak ada data
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasPages()): ?>
            <div class="flex flex-wrap items-center justify-center gap-1.5">

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->onFirstPage()): ?>
                    <span class="<?php echo e($base); ?> <?php echo e($disabled); ?>" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                <?php elseif($livewire): ?>
                    <button type="button" wire:click="previousPage('<?php echo e($pageName); ?>')" wire:loading.attr="disabled"
                            class="<?php echo e($base); ?> <?php echo e($idle); ?>" aria-label="Halaman sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                <?php else: ?>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev"
                       class="<?php echo e($base); ?> <?php echo e($idle); ?>" aria-label="Halaman sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item === '...'): ?>
                        <span class="inline-flex items-center justify-center min-w-[2.25rem] h-9 text-gray-400">…</span>
                    <?php elseif($item === $current): ?>
                        <span class="<?php echo e($base); ?> <?php echo e($active); ?>" aria-current="page"><?php echo e($item); ?></span>
                    <?php elseif($livewire): ?>
                        <button type="button" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'page-'.e($pageName).'-'.e($item).''; ?>wire:key="page-<?php echo e($pageName); ?>-<?php echo e($item); ?>"
                                wire:click="gotoPage(<?php echo e($item); ?>, '<?php echo e($pageName); ?>')"
                                class="<?php echo e($base); ?> <?php echo e($idle); ?>" aria-label="Ke halaman <?php echo e($item); ?>">
                            <?php echo e($item); ?>

                        </button>
                    <?php else: ?>
                        <a href="<?php echo e($paginator->url($item)); ?>"
                           class="<?php echo e($base); ?> <?php echo e($idle); ?>" aria-label="Ke halaman <?php echo e($item); ?>">
                            <?php echo e($item); ?>

                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasMorePages()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livewire): ?>
                        <button type="button" wire:click="nextPage('<?php echo e($pageName); ?>')" wire:loading.attr="disabled"
                                class="<?php echo e($base); ?> <?php echo e($idle); ?>" aria-label="Halaman berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next"
                           class="<?php echo e($base); ?> <?php echo e($idle); ?>" aria-label="Halaman berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <span class="<?php echo e($base); ?> <?php echo e($disabled); ?>" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </nav>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/components/custom-pagination.blade.php ENDPATH**/ ?>