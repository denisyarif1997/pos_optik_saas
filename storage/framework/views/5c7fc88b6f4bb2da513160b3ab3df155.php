<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resep <?php echo e($rx->customer?->name); ?></title>
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
    <div class="center"><b>RESEP KACAMATA</b></div>
    <hr>
    <div>Pasien: <?php echo e($rx->customer?->name ?? '-'); ?><br>Tanggal: <?php echo e($rx->checked_at?->format('d/m/Y') ?? $rx->created_at->format('d/m/Y')); ?><br>PD: <?php echo e($rx->pd ?? '-'); ?></div>
    <hr>
    <table>
        <tr><td></td><td class="center"><b>SPH</b></td><td class="center"><b>CYL</b></td><td class="center"><b>AXIS</b></td><td class="center"><b>ADD</b></td></tr>
        <tr><td><b>OD</b></td><td class="center"><?php echo e($rx->od_sph ?? '-'); ?></td><td class="center"><?php echo e($rx->od_cyl ?? '-'); ?></td><td class="center"><?php echo e($rx->od_axis ?? '-'); ?></td><td class="center"><?php echo e($rx->od_add ?? '-'); ?></td></tr>
        <tr><td><b>OS</b></td><td class="center"><?php echo e($rx->os_sph ?? '-'); ?></td><td class="center"><?php echo e($rx->os_cyl ?? '-'); ?></td><td class="center"><?php echo e($rx->os_axis ?? '-'); ?></td><td class="center"><?php echo e($rx->os_add ?? '-'); ?></td></tr>
    </table>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rx->notes): ?>
        <hr>
        <div>Catatan: <?php echo e($rx->notes); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <hr>
    <div class="center">Dokter/Refractionist</div>
    <div class="center" style="margin-top:40px">____________________</div>
    <hr>
    <div class="center"><?php echo e($store->receipt_note ?? 'Terima kasih'); ?></div>
    <div class="center" style="margin-top:12px"><button onclick="window.print()">Cetak Ulang</button></div>
</body>
</html><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/print/prescription.blade.php ENDPATH**/ ?>