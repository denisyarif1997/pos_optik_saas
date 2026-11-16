<div class="grid xl:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <h1 class="font-semibold">Retur Penjualan <span class="text-xs font-normal text-slate-400">• stok kembali, tercatat</span></h1>
        <div class="flex gap-2">
            <input type="text" wire:model="code" placeholder="Kode nota, cth JUAL-..." class="flex-1 rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button wire:click="findSale" class="bg-emerald-500 text-white text-sm font-bold px-4 rounded-lg">Cari</button>
        </div>
        @error('code') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        @if ($sale)
            <div class="rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm">
                <b>{{ $sale->code }}</b> • Rp {{ number_format($sale->total, 0, ',', '.') }}
                <div class="mt-2 space-y-1">@foreach ($sale->items as $it)<div>• {{ $it->product?->name }} × {{ $it->qty }}</div>@endforeach</div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <select wire:model="product_id" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5 text-sm">
                    <option value="">— Produk —</option>
                    @foreach ($sale->items as $it)<option value="{{ $it->product_id }}">{{ $it->product?->name }}</option>@endforeach
                </select>
                <input type="number" min="1" wire:model="qty" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5" placeholder="Qty">
            </div>
            <input type="text" wire:model="reason" placeholder="Alasan retur" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button wire:click="save" class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">Simpan Retur →</button>
        @endif
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Riwayat Retur</h2>
        @forelse ($history as $r)
            <div class="text-sm py-2 border-b border-slate-50 last:border-0"><span class="text-slate-500">No. Transaksi:</span> <b>{{ $r->sale?->code ?? '-' }}</b> • qty {{ $r->qty }} • Rp {{ number_format($r->refund_amount, 0, ',', '.') }}</div>
        @empty
            <p class="text-sm text-slate-400 text-center py-4">Belum ada retur.</p>
        @endforelse
    </div>
</div>


