<div class="grid xl:grid-cols-3 gap-4">
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-xl font-semibold">Nota {{ $sale->code }}</h1>
                <p class="text-xs text-slate-500">{{ $sale->sold_at->format('d M Y H:i') }} • {{ $sale->customer?->name ?? 'Umum' }}</p>
            </div>
            <span class="ml-auto text-[11px] font-bold px-3 py-1.5 rounded-full {{ $sale->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $sale->payment_status }}</span>
            <a href="{{ route('print.sale', $sale->id) }}" target="_blank" class="text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-500 text-white">🖨️ Cetak</a>
            <a href="{{ route('sales.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </div>
        <div class="mt-4 space-y-2 text-sm">
            @foreach ($sale->items as $it)
                @php
                    $returnedQty = (int) (($returnsByItem[$it->product_id]['qty'] ?? 0));
                    $remainingQty = (int) $it->qty - $returnedQty;
                    $itemRefund = (float) (($returnsByItem[$it->product_id]['amount'] ?? 0));
                @endphp
                <div class="flex justify-between gap-3 py-2 border-b border-slate-50 last:border-0">
                    <span>
                        {{ $it->product?->name }}
                        <span class="text-slate-400">× {{ $remainingQty }} / {{ $it->qty }}</span>
                        @if ($returnedQty > 0)
                            <span class="text-xs text-emerald-600 ml-2">(diretur {{ $returnedQty }})</span>
                        @endif
                    </span>
                    <div class="text-right">
                        <b>Rp {{ number_format($it->subtotal, 0, ',', '.') }}</b>
                        @if ($itemRefund > 0)
                            <span class="text-xs text-emerald-600 block">- Rp {{ number_format($itemRefund, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm space-y-1">
            <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span></div>
            @if ($sale->discount_total > 0)
                <div class="flex justify-between text-slate-500"><span>Diskon ({{ number_format($sale->discount_percent, 2, ',', '.') }}%)</span><span>- Rp {{ number_format($sale->discount_total, 0, ',', '.') }}</span></div>
            @endif
            @if ($sale->ppn_amount > 0)
                <div class="flex justify-between text-slate-500"><span>PPN ({{ number_format($sale->ppn_rate, 0) }}%)</span><span>+ Rp {{ number_format($sale->ppn_amount, 0, ',', '.') }}</span></div>
            @endif
            <div class="flex justify-between font-semibold text-lg"><span>Total</span><span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-emerald-700 font-bold"><span>Dibayar</span><span>Rp {{ number_format($sale->paid_total, 0, ',', '.') }}</span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <h2 class="font-semibold">Pembayaran</h2>
        @forelse ($sale->payments as $p)
            <div class="flex justify-between gap-3 text-sm py-2 border-b border-slate-50 last:border-0">
                <span>{{ strtoupper($p->method) }} • {{ $p->paid_at->format('d M H:i') }}<span class="block text-xs text-slate-500">{{ $p->cashAccount?->name ?? 'Akun lama tidak tercatat' }}</span></span>
                <b class="shrink-0">Rp {{ number_format($p->amount, 0, ',', '.') }}</b>
            </div>
        @empty
            <p class="text-sm text-slate-400">Belum ada pembayaran.</p>
        @endforelse
        @if ($canReceivePayment && $sale->payment_status !== 'lunas')
            <form wire:submit="addPayment" class="space-y-3 pt-2">
                <div class="grid grid-cols-2 gap-3">
                    <select wire:model="method" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5 text-sm">
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="card">Kartu</option>
                    </select>
                    <input type="number" wire:model="amount" placeholder="Nominal" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5">
                </div>
                <select wire:model="cashAccountId" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5 text-sm">
                    <option value="">Pilih akun kas</option>
                    @foreach ($cashAccounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->type === 'cash' ? 'Tunai' : 'Bank' }})</option>
                    @endforeach
                </select>
                @error('amount') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                @error('method') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                @error('cashAccountId') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">+ Catat Pelunasan</button>
            </form>
        @endif
    </div>
</div>


