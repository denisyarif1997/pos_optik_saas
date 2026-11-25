{{--  --}}<div class="grid xl:grid-cols-3 gap-4">
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 p-6 space-y-4">
      <div class="flex flex-wrap items-start justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold">Pesanan {{ $order->code }}</h1>
        <p class="text-sm text-slate-500">{{ $order->customer?->name ?? '-' }} • Lab {{ $order->lab_name ?? '-' }}</p>
    </div>

    <a href="{{ route('optical-orders.index') }}" wire:navigate
       class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5 shrink-0">
        <span>&larr;</span> Kembali
    </a>
</div>
        
        <div class="space-y-2 text-sm">
            @foreach ($order->items as $it)
                <div class="flex justify-between py-2 border-b border-slate-50 last:border-0">
                    <span>
                        {{ $it->description }} × {{ $it->qty }}
                        @if ((float) $it->discount > 0)
                            <span class="text-xs text-amber-600 ml-2">diskon -Rp {{ number_format($it->discount, 0, ',', '.') }}</span>
                        @endif
                        @if ((float) $it->ppn_amount > 0)
                            <span class="text-xs text-emerald-700 ml-2">PPN +Rp {{ number_format($it->ppn_amount, 0, ',', '.') }}</span>
                        @endif
                    </span>
                    <b>Rp {{ number_format($it->subtotal, 0, ',', '.') }}</b>
                </div>
            @endforeach
        </div>
        <div class="rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm">
            @if ($order->subtotal > 0)
                <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                @php
                    $itemDiscountTotal = (float) $order->items->sum('discount');
                    $transactionDiscount = max(0, (float) $order->discount_total - $itemDiscountTotal);
                @endphp
                @if ($itemDiscountTotal > 0)
                    <div class="flex justify-between text-slate-500"><span>Diskon item</span><span>- Rp {{ number_format($itemDiscountTotal, 0, ',', '.') }}</span></div>
                @endif
                @if ($transactionDiscount > 0)
                    <div class="flex justify-between text-slate-500"><span>Diskon transaksi</span><span>- Rp {{ number_format($transactionDiscount, 0, ',', '.') }}</span></div>
                @endif
                @if ($order->ppn_amount > 0)
                    <div class="flex justify-between text-slate-500"><span>PPN ({{ number_format($order->ppn_rate, 0) }}%)</span><span>+ Rp {{ number_format($order->ppn_amount, 0, ',', '.') }}</span></div>
                @endif
            @endif
            <div class="flex justify-between font-semibold"><span>Total</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-emerald-700 font-bold"><span>Sudah Bayar (DP)</span><span>Rp {{ number_format($order->dp_total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-red-600 font-bold"><span>Sisa / Piutang</span><span>Rp {{ number_format($order->receivable, 0, ',', '.') }}</span></div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold mb-3">Riwayat Pembayaran</h2>
            <div class="space-y-2 text-sm">
                @forelse ($order->payments as $p)
                    <div class="flex justify-between gap-3 py-1.5 border-b border-slate-50 last:border-0">
                        <span>{{ $p->paid_at->format('d/m/Y H:i') }} • {{ strtoupper($p->method) }} @if($p->receiver) ({{ $p->receiver->name }}) @endif<span class="block text-xs text-slate-500">{{ $p->cashAccount?->name ?? 'Akun lama tidak tercatat' }}</span></span>
                        <b>Rp {{ number_format($p->amount, 0, ',', '.') }}</b>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Belum ada pembayaran.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold mb-3">Status Pengerjaan</h2>
            <form wire:submit="updateStatus" class="space-y-3">
                <select wire:model="status" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                    <option value="DRAFT">Baru</option>
                    <option value="DIPROSES">Diproses</option>
                    <option value="SIAP">Siap diambil</option>
                    <option value="COMPLETED">Selesai</option>
                    <option value="BATAL">Dibatalkan</option>
                </select>
                <button
                    wire:loading.attr="disabled"
                    class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold flex items-center justify-center gap-2"
                >
                    <x-icon name="spinner" class="w-4 h-4 spin" wire:loading.remove wire:target="updateStatus"></x-icon>
                    <span wire:loading.remove wire:target="updateStatus">Perbarui Status</span>
                    <span wire:loading wire:target="updateStatus">Memproses...</span>
                </button>
            </form>
            @if ($this->statusMessage)
                <div class="mt-3 alert alert-success">
                    {{ $this->statusMessage }}
                </div>
            @endif
        </div>
        @if ($canReceivePayment && $order->receivable > 0)
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold mb-3">Catat Pembayaran (Cicilan)</h2>
            <form wire:submit="addPayment" class="space-y-3">
                <div>
                    <input type="number" step="0.01" wire:model="payAmount" placeholder="Jumlah (sisa Rp {{ number_format($order->receivable, 0, ',', '.') }})" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                    @error('payAmount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <select wire:model="payMethod" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="card">Kartu</option>
                    </select>
                </div>
                <div>
                    <select wire:model="cashAccountId" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                        <option value="">Pilih akun kas</option>
                        @foreach ($cashAccounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->type === 'cash' ? 'Tunai' : 'Bank' }})</option>
                        @endforeach
                    </select>
                    @error('cashAccountId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <input type="text" wire:model="payNotes" placeholder="Catatan (opsional)" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                </div>
                <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold">Simpan Pembayaran</button>
            </form>
        </div>
        @endif
        <div>
            <a href="{{ route('print.optical-order', $order->id) }}" target="_blank" class="block w-full text-center bg-slate-900 hover:bg-black text-white text-sm font-semibold py-2 rounded-lg">Cetak Nota Pesanan</a>
        </div>
    </div>
</div>