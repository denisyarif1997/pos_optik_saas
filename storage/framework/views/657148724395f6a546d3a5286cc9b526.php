<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan <?php echo e($order->code ?? $order->order_number); ?></title>
    <style>
        body { font-family: monospace; width: 300px; margin: 0 auto; font-size: 12px; color: #111; }
        .center { text-align: center; } .right { text-align: right; }
        hr { border: none; border-top: 1px dashed #999; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        @media print { button { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center"><b><?php echo e($store->name); ?></b><br><?php echo e($store->address ?? ''); ?><br><?php echo e($store->phone ?? ''); ?></div>
    <hr>
    <div>No: <?php echo e($order->code ?? $order->order_number); ?><br>Tanggal: <?php echo e($order->ordered_at?->format('d/m/Y') ?? '-'); ?><br>Janji: <?php echo e($order->promised_date?->format('d/m/Y') ?? '-'); ?><br>Pelanggan: <?php echo e($order->customer?->name ?? '-'); ?><br>Lab: <?php echo e($order->lab_name ?? '-'); ?><br>Status: <?php echo e($order->status); ?></div>
    <hr>
    <table>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <tr><td colspan="2"><?php echo e($it->description); ?> × <?php echo e($it->qty); ?> (<?php echo e($it->item_type); ?>)</td><td class="right"><?php echo e(number_format($it->subtotal, 0, ',', '.')); ?></td></tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </table>
    <hr>
    <table>
        <tr><td>Total</td><td class="right"><b><?php echo e(number_format($order->total, 0, ',', '.')); ?></b></td></tr>
        <tr><td>DP</td><td class="right"><?php echo e(number_format($order->dp_total, 0, ',', '.')); ?></td></tr>
        <tr><td>Sisa</td><td class="right"><b><?php echo e(number_format($order->total - $order->dp_total, 0, ',', '.')); ?></b></td></tr>
    </table>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->prescription): ?>
        <hr>
        <div class="center"><b>Resep</b></div>
        <div>OD: SPH <?php echo e($order->prescription->od_sph ?? '-'); ?> CYL <?php echo e($order->prescription->od_cyl ?? '-'); ?> AXIS <?php echo e($order->prescription->od_axis ?? '-'); ?> ADD <?php echo e($order->prescription->od_add ?? '-'); ?></div>
        <div>OS: SPH <?php echo e($order->prescription->os_sph ?? '-'); ?> CYL <?php echo e($order->prescription->os_cyl ?? '-'); ?> AXIS <?php echo e($order->prescription->os_axis ?? '-'); ?> ADD <?php echo e($order->prescription->os_add ?? '-'); ?></div>
        <div>PD: <?php echo e($order->prescription->pd ?? '-'); ?></div>
    <?php elseif(is_array($order->prescription_snapshot)): ?>
        <hr>
        <div class="center"><b>Resep (snapshot)</b></div>
        <?php $snap = $order->prescription_snapshot; ?>
        <div>OD: SPH <?php echo e($snap['od_sph'] ?? '-'); ?> CYL <?php echo e($snap['od_cyl'] ?? '-'); ?> AXIS <?php echo e($snap['od_axis'] ?? '-'); ?> ADD <?php echo e($snap['od_add'] ?? '-'); ?></div>
        <div>OS: SPH <?php echo e($snap['os_sph'] ?? '-'); ?> CYL <?php echo e($snap['os_cyl'] ?? '-'); ?> AXIS <?php echo e($snap['os_axis'] ?? '-'); ?> ADD <?php echo e($snap['os_add'] ?? '-'); ?></div>
        <div>PD: <?php echo e($snap['pd'] ?? '-'); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->work_notes): ?>
        <hr>
        <div>Catatan: <?php echo e($order->work_notes); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <hr>
    <div class="center"><?php echo e($store->receipt_note ?? 'Terima kasih atas kunjungan Anda'); ?></div>
    <div class="center" style="margin-top:12px"><button onclick="window.print()">Cetak Ulang</button></div>
</body>
</html><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/print/optical-order.blade.php ENDPATH**/ ?>