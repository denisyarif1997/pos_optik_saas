<!-- Wrapper Utama dengan Alpine.js untuk perhitungan Frontend -->
<div 
    class="flex flex-col lg:flex-row gap-6 items-start -m-2 sm:-m-4"
    x-data="{
        payAmount: <?php if ((object) ('pay_amount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('pay_amount'->value()); ?>')<?php echo e('pay_amount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('pay_amount'); ?>')<?php endif; ?>,
        total: <?php echo e((int) $totals['total']); ?>,
        get change() {
            return Math.max(0, this.payAmount - this.total);
        },
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }
    }"
>
    
    <!-- KIRI: Pencarian & Produk (Fleksibel menutupi ruang tersisa) -->
    <div class="flex-1 w-full flex flex-col h-[calc(100vh-80px)]">
        
        <!-- Search Bar Besar dengan Pop-Up Dropdown -->
        <div class="relative z-50" x-data="{ open: true }">
            <div class="soft-card p-4 flex gap-4 items-center relative z-10 bg-white">
                <div class="absolute inset-y-0 left-0 pl-8 flex items-center pointer-events-none">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'w-6 h-6 text-text-muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-6 h-6 text-text-muted']); ?>
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
                <!-- Input dengan Alpine Listener untuk Open/Close pop-up -->
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    @focus="open = true"
                    @click.away="open = false"
                    class="input pl-14 py-4 text-lg w-full font-medium border-transparent shadow-none focus:ring-0 focus:border-transparent bg-canvas/50 focus:bg-white transition-colors rounded-xl" 
                    placeholder="Ketik nama produk, SKU, atau scan Barcode disini..."
                    autofocus
                >
            </div>

            <!-- POP UP Hasil Pencarian (Melayang di bawah input) -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(strlen(trim($search)) > 0): ?>
                <div x-show="open" x-transition class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-line overflow-hidden max-h-[450px] overflow-y-auto">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($results->count()): ?>
                        <div class="p-2 space-y-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <!-- Atribut wire:key ditambahkan agar Livewire tidak lag saat update DOM -->
                                <button wire:click="addToCart(<?php echo e($p->id); ?>)" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'search-res-'.e($p->id).''; ?>wire:key="search-res-<?php echo e($p->id); ?>" class="w-full flex items-center justify-between text-left p-3.5 rounded-lg hover:bg-emerald-bg hover:text-emerald-dark transition-colors border border-transparent hover:border-emerald-soft group cursor-pointer">
                                    <div>
                                        <div class="font-bold text-[15px] group-hover:text-emerald text-text-main"><?php echo e($p->name); ?></div>
                                        <div class="text-[12px] text-text-secondary mt-1">
                                            SKU: <span class="font-mono bg-canvas px-1 py-0.5 rounded text-text-main"><?php echo e($p->sku ?? $p->barcode ?? '-'); ?></span> 
                                            &nbsp;&bull;&nbsp; Stok: <span class="<?php echo e($p->stock <= 0 ? 'text-error font-bold' : 'font-medium'); ?>"><?php echo e($p->stock); ?></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-text-main group-hover:text-emerald text-lg">Rp <?php echo e(number_format($p->sell_price, 0, ',', '.')); ?></div>
                                        <div class="text-[10px] text-text-muted group-hover:text-emerald opacity-0 group-hover:opacity-100 transition-opacity">Ketuk untuk tambah +</div>
                                    </div>
                                </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!-- State jika produk tidak ada -->
                        <div class="p-8 text-center text-text-muted bg-canvas/30">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'box','class' => 'w-10 h-10 mx-auto mb-3 opacity-40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'box','class' => 'w-10 h-10 mx-auto mb-3 opacity-40']); ?>
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
                            <p class="font-medium text-sm">Produk dengan kata kunci "<span class="text-text-main font-bold"><?php echo e($search); ?></span>" tidak ditemukan.</p>
                            <p class="text-[11px] mt-1">Periksa kembali ejaan atau coba scan ulang barcode.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Area Latar Belakang Kiri (Ditampilkan saat Pop-up tertutup / kosong) -->
        <div class="flex-1 overflow-y-auto pb-6 flex flex-col items-center justify-center text-text-muted/60 relative z-0">
            <div class="w-24 h-24 mb-6 rounded-3xl bg-emerald-bg text-emerald flex items-center justify-center shadow-sm border border-emerald-soft">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shopping-bag','class' => 'w-12 h-12']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-bag','class' => 'w-12 h-12']); ?>
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
            <h3 class="text-xl font-bold text-text-secondary mb-2">Kasir Siap Digunakan</h3>
            <p class="text-sm max-w-sm text-center">Gunakan alat scanner Barcode atau ketik manual nama produk di kolom pencarian atas untuk memulai transaksi.</p>
        </div>
    </div>


    <!-- KANAN: Keranjang & Pembayaran (Fixed Panel) -->
    <div class="w-full lg:w-[420px] soft-card flex flex-col h-[calc(100vh-80px)] sticky top-4 shadow-xl shrink-0 overflow-hidden">
        
        <!-- Toggle Mode Penjualan -->
        <div class="p-3 bg-canvas border-b border-line flex gap-2">
            <button wire:click="$set('mode', 'sale')" class="flex-1 py-2.5 rounded-lg font-semibold text-sm transition-all <?php echo e($mode === 'sale' ? 'bg-emerald text-white shadow-md' : 'bg-surface text-text-secondary hover:bg-emerald-soft'); ?>">
                Jual Langsung
            </button>
            <button wire:click="$set('mode', 'optical')" class="flex-1 py-2.5 rounded-lg font-semibold text-sm transition-all <?php echo e($mode === 'optical' ? 'bg-emerald text-white shadow-md' : 'bg-surface text-text-secondary hover:bg-emerald-soft'); ?>">
                Pesanan Optik
            </button>
        </div>

        <!-- Daftar Item Keranjang -->
        <div class="flex-1 overflow-y-auto p-4 bg-canvas/30">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($cartDetailed)): ?>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cartDetailed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="bg-white border border-line rounded-xl p-3 shadow-sm flex flex-col gap-2">
                            <div class="flex justify-between items-start gap-2">
                                <h5 class="font-semibold text-sm text-text-main leading-tight"><?php echo e($row['product']->name); ?></h5>
                                <button wire:click="removeRow(<?php echo e($row['index']); ?>)" class="text-text-muted hover:text-error p-1 -mt-1 -mr-1 transition-colors">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-4 h-4']); ?>
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
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-medium text-text-secondary">
                                    Rp <?php echo e(number_format($row['product']->sell_price, 0, ',', '.')); ?>

                                </div>
                                
                                <!-- QTY Controler (Memanggil method increment/decrement dari Livewire) -->
                                <div class="flex items-center border border-line rounded-lg overflow-hidden bg-canvas">
                                    <button wire:click="decrementQty(<?php echo e($row['index']); ?>)" class="px-2.5 py-1 text-text-main hover:bg-white transition-colors">-</button>
                                    <div class="px-3 py-1 font-semibold text-sm bg-white border-x border-line"><?php echo e($row['qty']); ?></div>
                                    <button wire:click="incrementQty(<?php echo e($row['index']); ?>)" class="px-2.5 py-1 text-text-main hover:bg-white transition-colors">+</button>
                                </div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="h-full flex flex-col items-center justify-center text-text-muted opacity-60">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shopping-cart','class' => 'w-12 h-12 mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-cart','class' => 'w-12 h-12 mb-3']); ?>
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
                    <p class="text-sm">Keranjang kosong</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Ringkasan & Form Pembayaran (Posisi di Bawah Terus) -->
        <div class="p-5 border-t border-line bg-white shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.05)]">
            
            <div class="mb-4">
                <select wire:model="customer_id" class="input py-2.5 bg-canvas font-medium text-sm w-full">
                    <option value="">-- Pilih Pelanggan (Opsional) --</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?> (<?php echo e($c->phone); ?>)</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>

            <!-- Pesanan Optik Fields -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mode === 'optical'): ?>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="label text-[10px]">Lab Supplier</label>
                        <input type="text" wire:model="lab_name" class="input py-2 text-sm" placeholder="Nama Lab">
                    </div>
                    <div>
                        <label class="label text-[10px]">Estimasi Selesai</label>
                        <input type="date" wire:model="promised_date" class="input py-2 text-sm">
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Ringkasan Total (Big Display) -->
            <div class="flex items-end justify-between mb-4 border-b border-line pb-4">
                <div>
                    <div class="text-sm text-text-secondary mb-1">Total Tagihan</div>
                    <div class="text-3xl font-bold text-text-main">
                        Rp <?php echo e(number_format($totals['total'], 0, ',', '.')); ?>

                    </div>
                </div>
            </div>

            <!-- Perhitungan Pembayaran (Instant via Alpine.js) -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mode === 'sale'): ?>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border border-line bg-canvas hover:border-emerald cursor-pointer transition-colors has-[:checked]:bg-emerald-bg has-[:checked]:border-emerald has-[:checked]:text-emerald-dark">
                        <input type="radio" wire:model="payment_method" value="cash" class="sr-only">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'cash','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'cash','class' => 'w-5 h-5']); ?>
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
                        <span class="font-semibold text-sm">Tunai</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border border-line bg-canvas hover:border-emerald cursor-pointer transition-colors has-[:checked]:bg-emerald-bg has-[:checked]:border-emerald has-[:checked]:text-emerald-dark">
                        <input type="radio" wire:model="payment_method" value="non_cash" class="sr-only">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'credit-card','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'w-5 h-5']); ?>
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
                        <span class="font-semibold text-sm">Non-Tunai</span>
                    </label>
                </div>

               <div class="mb-4">
    <label for="payAmount" class="label">Jumlah Uang Diterima</label>

    <!-- Input dengan prefix Rp, format ribuan otomatis -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-lg font-bold text-emerald-dark/70 pointer-events-none">Rp</span>
        <input
            id="payAmount"
            type="text"
            inputmode="numeric"
            autocomplete="off"
            class="input pl-12 py-3 text-lg font-bold text-emerald-dark bg-emerald-bg/50 focus:bg-white text-right tabular-nums"
            placeholder="0"
            :value="payAmount ? new Intl.NumberFormat('id-ID').format(payAmount) : ''"
            @input="
                payAmount = parseInt($event.target.value.replace(/\D/g, '')) || 0;
                $event.target.value = payAmount ? new Intl.NumberFormat('id-ID').format(payAmount) : '';
            "
            @focus="$event.target.select()"
        >
    </div>

    <!-- Tombol nominal cepat -->
    <div class="mt-2 flex flex-wrap gap-1.5">
        <button type="button" class="btn-ghost !px-3 !py-1.5 text-xs" @click="payAmount = total">Uang pas</button>
        <template x-for="nominal in [50000, 100000, 200000]" :key="nominal">
            <button type="button" class="btn-ghost !px-3 !py-1.5 text-xs"
                    @click="payAmount = nominal"
                    x-text="new Intl.NumberFormat('id-ID').format(nominal)"></button>
        </template>
        <button type="button" class="btn-ghost !px-3 !py-1.5 text-xs ml-auto" @click="payAmount = 0">Reset</button>
    </div>

    <!-- Kembalian / kurang -->
    <div class="mt-3 text-sm font-medium flex justify-between px-1"
         :class="payAmount === 0 ? 'text-text-secondary' : (payAmount < total ? 'text-error' : 'text-emerald')">
        <span x-text="payAmount > 0 && payAmount < total ? 'Kurang:' : 'Kembalian:'"></span>
        <span class="font-bold tabular-nums"
              x-text="formatRupiah(payAmount > 0 && payAmount < total ? total - payAmount : change)"></span>
    </div>
</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <button wire:click="checkout"
                class="btn-primary w-full py-4 text-lg shadow-lg <?php echo e(count($cartDetailed) === 0 ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                <?php echo e(count($cartDetailed) === 0 ? 'disabled' : ''); ?>>
                <?php echo e($mode === 'sale' ? 'Bayar Transaksi' : 'Buat Pesanan Optik'); ?>

            </button>

        </div>
    </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/pos/cashier.blade.php ENDPATH**/ ?>