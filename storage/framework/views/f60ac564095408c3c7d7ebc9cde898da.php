<div class="space-y-5">
  <!-- Quick Actions -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
    <x-quick-action-card href="<?php echo e(route("pos.cashier")); ?>" label="Transaksi Baru" description="Kasir & penjualan" icon="credit-card" color="accent" />
    <x-quick-action-card href="<?php echo e(route("customers.create")); ?>" label="Tambah Pelanggan" description="Data pelanggan baru" icon="users" color="success" />
    <x-quick-action-card href="<?php echo e(route("products.create")); ?>" label="Tambah Produk" description="Katalog produk" icon="box" color="warning" />
    <x-quick-action-card href="<?php echo e(route("inventory.index")); ?>" label="Cek Stok" description="Inventori lensa" icon="layers" color="info" />
    <x-quick-action-card href="<?php echo e(route("optical-orders.index")); ?>" label="Pesanan Kacamata" description="Servis optik" icon="glasses" color="accent" />
    <x-quick-action-card href="<?php echo e(route("sales.index")); ?>" label="Riwayat Jual" description="Transaksi sebelumnya" icon="wallet" color="success" />
  </div>

  <!-- Stat Cards -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    <x-stat-card label="Penjualan Hari Ini" value="Rp <?php echo e(number_format($todaySales, 0, ",", ".")); ?>" subtext="<?php echo e($todayCount); ?> transaksi &middot; <?php echo e($growth); ?>% vs kemarin" icon="wallet" color="accent" :trend="['label' => $growth >= 0 ? 'Naik <?php echo e($growth); ?>%' : 'Turun <?php echo e(abs($growth)); ?>%', 'class' => $growth >= 0 ? 'text-success' : 'text-error']" />
    <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Transaksi','value' => ''.e($todayCount).'','subtext' => 'Hari ini','icon' => 'credit-card','color' => 'accent']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Transaksi','value' => ''.e($todayCount).'','subtext' => 'Hari ini','icon' => 'credit-card','color' => 'accent']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Pesanan Aktif','value' => ''.e($openOrders).'','subtext' => 'Belum selesai','icon' => 'glasses','color' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pesanan Aktif','value' => ''.e($openOrders).'','subtext' => 'Belum selesai','icon' => 'glasses','color' => 'warning']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Stok Menipis','value' => ''.e($lowStock).'','subtext' => 'Perlu restock','icon' => 'box','color' => 'error']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Stok Menipis','value' => ''.e($lowStock).'','subtext' => 'Perlu restock','icon' => 'box','color' => 'error']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    <x-stat-card label="Piutang" value="Rp <?php echo e(number_format($unpaid, 0, ",", ".")); ?>" subtext="Belum lunas" icon="wallet" color="info" />
  </div>
<!-- Grid 2 kolom: Low Stock + Pending Orders -->
<div class="grid lg:grid-cols-2 gap-3">
  <div class="stat-card">
    <div class="flex items-center justify-between mb-3">
      <div class="text-sm font-semibold textxx-text-main">Stok Menipis (<?php echo e($lowStock); ?>)</div>
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lowStock > 0): ?>
        <a href="<?php echo e(route("inventory.index")); ?>" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua &rarr;</a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
      <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
        <span class="text-text-main"><?php echo e($p->name); ?></span>
        <span class="text-text-secondary">Sisa <?php echo e($p->stock); ?></span>
      </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
      <p class="text-sm text-text-secondary">Stok aman. Semua produk di atas minimum peringatan.</p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>

  <div class="stat-card">
    <div class="flex items-center justify-between mb-3">
      <div class="text-sm font-semibold text-text-main">Pesanan Aktif (<?php echo e($openOrders); ?>)</div>
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($openOrders > 0): ?>
        <a href="<?php echo e(route("optical-orders.index")); ?>" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua &rarr;</a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
      <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
        <div>
          <div class="text-text-main"><?php echo e($o->code); ?> &middot; <?php echo e($o->customer?->name ?? "-"); ?></div>
          <div class="text-[10px] text-text-secondary"><?php echo e($o->status); ?></div>
        </div>
        <span class="status-badge <?php echo e($o->status === 'siap_diambil' ? 'success' : ($o->status === 'DIBATALKAN' || $o->status === 'dibatalkan' ? 'error' : 'info')); ?>">
          <?php echo e($o->status); ?>

        </span>
      </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
      <p class="text-sm text-text-secondary">Tidak ada pesanan aktif.</p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</div>

<!-- Grid 2 kolom: Weekly Chart + Ready Orders -->
<div class="grid lg:grid-cols-2 gap-3">
  <div class="stat-card">
    <div class="text-sm font-semibold text-text-main mb-3">Penjualan 7 Hari Terakhir</div>
    <div class="flex items-end gap-2 h-32">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekly; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="flex-1 flex flex-col items-center gap-1">
          <div class="w-full bg-accent rounded-t" style="height: <?php echo e($maxWeekly > 0 ? round($w['value'] / $maxWeekly * 100) : 0); ?>%"></div>
          <div class="text-[10px] text-text-secondary"><?php echo e($w['label']); ?></div>
        </div>
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
  </div>

  <div class="stat-card">
    <div class="text-sm font-semibold text-text-main mb-3">Pesanan Siap Diambil (<?php echo e($readyOrders->count()); ?>)</div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $readyOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
      <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
        <span class="text-text-main"><?php echo e($o->code); ?> &middot; <?php echo e($o->customer?->name ?? "-"); ?></span>
        <span class="status-badge success">Siap</span>
      </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
      <p class="text-sm text-text-secondary">Tidak ada pesanan siap diambil.</p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</div>

<!-- Recent Sales -->
<div class="stat-card">
  <div class="flex items-center justify-between mb-3">
    <div class="text-sm font-semibold text-text-main">Transaksi Terakhir</div>
    <a href="<?php echo e(route("sales.index")); ?>" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua &rarr;</a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left">
          <th class="py-2.5 pr-3 text-xs font-semibold text-text-secondary uppercase">Kode</th>
          <th class="py-2.5 pr-3 text-xs font-semibold text-text-secondary uppercase">Waktu</th>
          <th class="py-2.5 text-right pr-3 text-xs font-semibold text-text-secondary uppercase">Total</th>
          <th class="py-2.5 text-xs font-semibold text-text-secondary uppercase">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
          <tr class="border-b border-slate-100 last:border-0">
            <td class="py-2.5 pr-3 text-text-main"><?php echo e($s->code); ?></td>
            <td class="py-2.5 pr-3 text-text-secondary"><?php echo e($s->sold_at->format("d M H:i")); ?></td>
            <td class="py-2.5 text-right pr-3 text-text-main">Rp <?php echo e(number_format($s->total, 0, ",", ".")); ?></td>
            <td class="py-2.5">
              <span class="status-badge <?php echo e($s->payment_status === 'lunas' ? 'success' : ($s->payment_status === 'dp' ? 'warning' : 'error')); ?>">
                <?php echo e($s->payment_status); ?>

              </span>
            </td>
          </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
          <tr><td colspan="4" class="py-4 text-center text-text-secondary">Belum ada transaksi.</td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </tbody>
    </table>
  </div>
</div><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/livewire/dashboard/index.blade.php ENDPATH**/ ?>