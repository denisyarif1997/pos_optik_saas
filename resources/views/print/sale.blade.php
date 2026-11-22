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
            @php
                $returnedQty = (int) (($returnsByItem[$it->product_id]['qty'] ?? 0));
                $remainingQty = (int) $it->qty - $returnedQty;
            @endphp
            <tr><td colspan="2">{{ $it->product?->name }} × {{ $remainingQty }} / {{ $it->qty }}</td><td class="right">{{ number_format($it->subtotal, 0, ',', '.') }}</td></tr>
        @endforeach
    </table>
    <hr>
    <table>
        <tr><td>Subtotal</td><td class="right">{{ number_format($sale->subtotal, 0, ',', '.') }}</td></tr>
        @if ($sale->discount_total > 0)
            <tr><td>Diskon ({{ number_format($sale->discount_percent, 2, ',', '.') }}%)</td><td class="right">-{{ number_format($sale->discount_total, 0, ',', '.') }}</td></tr>
        @endif
        @if ($sale->ppn_amount > 0)
            <tr><td>PPN ({{ number_format($sale->ppn_rate, 0) }}%)</td><td class="right">{{ number_format($sale->ppn_amount, 0, ',', '.') }}</td></tr>
        @endif
        @if ($totalRefund > 0)
            <tr><td>Retur</td><td class="right">-{{ number_format($totalRefund, 0, ',', '.') }}</td></tr>
        @endif
        <tr><td>Total</td><td class="right"><b>{{ number_format(max(0, (float) $sale->total - $totalRefund), 0, ',', '.') }}</b></td></tr>
        <tr><td>Bayar</td><td class="right">{{ number_format($sale->paid_total, 0, ',', '.') }}</td></tr>
        <tr><td>Status</td><td class="right">{{ $sale->payment_status }}</td></tr>
    </table>
    @if ($sale->payments->isNotEmpty())
        <hr>
        <div>Pembayaran:</div>
        @foreach ($sale->payments as $payment)
            <div>{{ strtoupper($payment->method) }} - {{ $payment->cashAccount?->name ?? 'Akun lama tidak tercatat' }}: {{ number_format($payment->amount, 0, ',', '.') }}</div>
        @endforeach
    @endif
    <hr>
    <div class="center">{{ $store->receipt_note ?? 'Terima kasih atas kunjungan Anda' }}</div>
    <div class="center" style="margin-top:12px"><button onclick="window.print()">Cetak Ulang</button></div>
</body>
</html>
