<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota {{ $sale->code }}</title>
    <style>
        body { font-family: monospace; width: 280px; margin: 0 auto; font-size: 12px; color: #111; }
        .center { text-align: center; } .right { text-align: right; }
        hr { border: none; border-top: 1px dashed #999; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        @media print { button { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center"><b>{{ $store->name }}</b><br>{{ $store->address ?? '' }}<br>{{ $store->phone ?? '' }}</div>
    <hr>
    <div>No: {{ $sale->code }}<br>Waktu: {{ $sale->sold_at->format('d/m/Y H:i') }}<br>Pelanggan: {{ $sale->customer?->name ?? 'Umum' }}</div>
    <hr>
    <table>
        @foreach ($sale->items as $it)
            <tr><td colspan="2">{{ $it->product?->name }} × {{ $it->qty }}</td><td class="right">{{ number_format($it->subtotal, 0, ',', '.') }}</td></tr>
        @endforeach
    </table>
    <hr>
    <table>
        <tr><td>Total</td><td class="right"><b>{{ number_format($sale->total, 0, ',', '.') }}</b></td></tr>
        <tr><td>Bayar</td><td class="right">{{ number_format($sale->paid_total, 0, ',', '.') }}</td></tr>
        <tr><td>Status</td><td class="right">{{ $sale->payment_status }}</td></tr>
    </table>
    <hr>
    <div class="center">{{ $store->receipt_note ?? 'Terima kasih atas kunjungan Anda' }}</div>
    <div class="center" style="margin-top:12px"><button onclick="window.print()">Cetak Ulang</button></div>
</body>
</html>
