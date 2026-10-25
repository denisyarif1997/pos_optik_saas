<div class="grid xl:grid-cols-3 gap-4">
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-xl font-semibold">Nota {{ $sale->code }}</h1>
                <p class="text-xs text-slate-500">{{ $sale->sold_at->format('d M Y H:i') }} • {{ $sale->customer?->name ?? 'Umum' }}</p>
            </div>
            <span class="ml-auto text-[11px] font-bold px-3 py-1.5 rounded-full {{ $sale->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $sale->payment_status }}</span>
            <a href="{{ route('print.sale', $sale->id) }}" target="_blank" class="text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-500 text-white">🖨️ Cetak</a>
        </div>
        <div class="mt-4 space-y-2 text-sm">
            @foreach ($sale->items as $it)
                <div class="flex justify-between gap-3 py-2 border-b border-slate-50 last:border-0">
                    <span>{{ $it->product?->name }} <span class="text-slate-400">× {{ $it->qty }}</span></span>
                    <b>Rp {{ number_format($it->subtotal, 0, ',', '.') }}</b>
                </div>
            @endforeach
        </div>
        <div class="mt-4 rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm space-y-1">
            <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between font-semibold text-lg"><span>Total</span><span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-emerald-700 font-bold"><span>Dibayar</span><span>Rp {{ number_format($sale->paid_total, 0, ',', '.') }}</span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <h2 class="font-semibold">Pembayaran</h2>
        @forelse ($sale->payments as $p)
            <div class="flex justify-between text-sm py-2 border-b border-slate-50 last:border-0"><span>{{ strtoupper($p->method) }} • {{ $p->paid_at->format('d M H:i') }}</span><b>Rp {{ number_format($p->amount, 0, ',', '.') }}</b></div>
        @empty
            <p class="text-sm text-slate-400">Belum ada pembayaran.</p>
        @endforelse
        @if ($sale->payment_status !== 'lunas')
            <form wire:submit="addPayment" class="space-y-3 pt-2">
                <div class="grid grid-cols-2 gap-3">
                    <select wire:model="method" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5 text-sm">
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                    <input type="number" wire:model="amount" placeholder="Nominal" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5">
                </div>
                @error('amount') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">+ Catat Pelunasan</button>
            </form>
        @endif
    </div>
</div>


