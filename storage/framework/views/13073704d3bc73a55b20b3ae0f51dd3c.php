<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota <?php echo e($sale->code); ?></title>
    <style>
        body { font-family: monospace; width: 280px; margin: 0 auto; font-size: 12px; color: #111; }
        .center { text-align: center; } .right { text-align: right; }
        hr { border: none; border-top: 1px dashed #999; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        @media print { button { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center"><b><?php echo e($store->name); ?></b><br><?php echo e($store->address ?? ''); ?><br><?php echo e($store->phone ?? ''); ?></div>
    <hr>
    <div>No: <?php echo e($sale->code); ?><br>Waktu: <?php echo e($sale->sold_at->format('d/m/Y H:i')); ?><br>Pelanggan: <?php echo e($sale->customer?->name ?? 'Umum'); ?></div>
    <hr>
    <table>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <tr><td colspan="2"><?php echo e($it->product?->name); ?> × <?php echo e($it->qty); ?></td><td class="right"><?php echo e(number_format($it->subtotal, 0, ',', '.')); ?></td></tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </table>
    <hr>
    <table>
        <tr><td>Subtotal</td><td class="right"><?php echo e(number_format($sale->subtotal, 0, ',', '.')); ?></td></tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->discount_total > 0): ?>
            <tr><td>Diskon (<?php echo e(number_format($sale->discount_percent, 2, ',', '.')); ?>%)</td><td class="right">-<?php echo e(number_format($sale->discount_total, 0, ',', '.')); ?></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->ppn_amount > 0): ?>
            <tr><td>PPN (<?php echo e(number_format($sale->ppn_rate, 0)); ?>%)</td><td class="right"><?php echo e(number_format($sale->ppn_amount, 0, ',', '.')); ?></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <tr><td>Total</td><td class="right"><b><?php echo e(number_format($sale->total, 0, ',', '.')); ?></b></td></tr>
        <tr><td>Bayar</td><td class="right"><?php echo e(number_format($sale->paid_total, 0, ',', '.')); ?></td></tr>
        <tr><td>Status</td><td class="right"><?php echo e($sale->payment_status); ?></td></tr>
    </table>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->payments->isNotEmpty()): ?>
        <hr>
        <div>Pembayaran:</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sale->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div><?php echo e(strtoupper($payment->method)); ?> - <?php echo e($payment->cashAccount?->name ?? 'Akun lama tidak tercatat'); ?>: <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <hr>
    <div class="center"><?php echo e($store->receipt_note ?? 'Terima kasih atas kunjungan Anda'); ?></div>
    <div class="center" style="margin-top:12px"><button onclick="window.print()">Cetak Ulang</button></div>
</body>
</html>
<?php /**PATH C:\webproject\pos_optik_saas-main\pos_optik_saas-main\resources\views/print/sale.blade.php ENDPATH**/ ?>