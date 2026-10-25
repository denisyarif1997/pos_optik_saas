<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan {{ $order->code ?? $order->order_number }}</title>
    <style>
        body { font-family: monospace; width: 300px; margin: 0 auto; font-size: 12px; color: #111; }
        .center { text-align: center; } .right { text-align: right; }
        hr { border: none; border-top: 1px dashed #999; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        @media print { button { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center"><b>{{ $store->name }}</b><br>{{ $store->address ?? '' }}<br>{{ $store->phone ?? '' }}</div>
    <hr>
    <div>No: {{ $order->code ?? $order->order_number }}<br>Tanggal: {{ $order->ordered_at?->format('d/m/Y') ?? '-' }}<br>Janji: {{ $order->promised_date?->format('d/m/Y') ?? '-' }}<br>Pelanggan: {{ $order->customer?->name ?? '-' }}<br>Lab: {{ $order->lab_name ?? '-' }}<br>Status: {{ $order->status }}</div>
    <hr>
    <table>
        @foreach ($order->items as $it)
            <tr><td colspan="2">{{ $it->description }} × {{ $it->qty }} ({{ $it->item_type }})</td><td class="right">{{ number_format($it->subtotal, 0, ',', '.') }}</td></tr>
        @endforeach
    </table>
    <hr>
    <table>
        <tr><td>Total</td><td class="right"><b>{{ number_format($order->total, 0, ',', '.') }}</b></td></tr>
        <tr><td>DP</td><td class="right">{{ number_format($order->dp_total, 0, ',', '.') }}</td></tr>
        <tr><td>Sisa</td><td class="right"><b>{{ number_format($order->total - $order->dp_total, 0, ',', '.') }}</b></td></tr>
    </table>
    @if ($order->prescription)
        <hr>
        <div class="center"><b>Resep</b></div>
        <div>OD: SPH {{ $order->prescription->od_sph ?? '-' }} CYL {{ $order->prescription->od_cyl ?? '-' }} AXIS {{ $order->prescription->od_axis ?? '-' }} ADD {{ $order->prescription->od_add ?? '-' }}</div>
        <div>OS: SPH {{ $order->prescription->os_sph ?? '-' }} CYL {{ $order->prescription->os_cyl ?? '-' }} AXIS {{ $order->prescription->os_axis ?? '-' }} ADD {{ $order->prescription->os_add ?? '-' }}</div>
        <div>PD: {{ $order->prescription->pd ?? '-' }}</div>
    @elseif (is_array($order->prescription_snapshot))
        <hr>
        <div class="center"><b>Resep (snapshot)</b></div>
        @php $snap = $order->prescription_snapshot; @endphp
        <div>OD: SPH {{ $snap['od_sph'] ?? '-' }} CYL {{ $snap['od_cyl'] ?? '-' }} AXIS {{ $snap['od_axis'] ?? '-' }} ADD {{ $snap['od_add'] ?? '-' }}</div>
        <div>OS: SPH {{ $snap['os_sph'] ?? '-' }} CYL {{ $snap['os_cyl'] ?? '-' }} AXIS {{ $snap['os_axis'] ?? '-' }} ADD {{ $snap['os_add'] ?? '-' }}</div>
        <div>PD: {{ $snap['pd'] ?? '-' }}</div>
    @endif
    @if ($order->work_notes)
        <hr>
        <div>Catatan: {{ $order->work_notes }}</div>
    @endif
    <hr>
    <div class="center">{{ $store->receipt_note ?? 'Terima kasih atas kunjungan Anda' }}</div>
    <div class="center" style="margin-top:12px"><button onclick="window.print()">Cetak Ulang</button></div>
</body>
</html>