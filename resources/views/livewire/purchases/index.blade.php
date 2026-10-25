<div class="grid xl:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <h1 class="font-semibold">Pembelian Baru <span class="text-xs font-normal text-slate-400">• stok otomatis bertambah</span></h1>
        <select wire:model="supplier_id" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <option value="">— Pilih supplier —</option>
            @foreach ($suppliers as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
        </select>
        <input type="text" wire:model.live="productSearch" placeholder="Cari produk..." class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
        @if ($products->count())
            <div class="space-y-2">
                @foreach ($products as $p)
                    <button wire:click="addProduct({{ $p->id }})" class="w-full text-left text-sm rounded-lg border border-emerald-100 px-3 py-2 hover:bg-emerald-50"><b>{{ $p->name }}</b> <span class="text-slate-400">• stok {{ $p->stock }}</span></button>
                @endforeach
            </div>
        @endif
        @foreach ($detailed as $row)
            <div class="grid grid-cols-12 gap-2 items-center text-sm rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2">
                <span class="col-span-5 font-bold truncate">{{ $row['product']->name }}</span>
                <input type="number" min="1" wire:model.live="cart.{{ $row['index'] }}.qty" class="col-span-2 rounded-xl border border-emerald-100 px-2 py-1.5">
                <input type="number" min="0" wire:model.live="cart.{{ $row['index'] }}.buy_price" placeholder="Harga beli" class="col-span-4 rounded-xl border border-emerald-100 px-2 py-1.5">
                <button wire:click="removeRow({{ $row['index'] }})" class="col-span-1 text-red-500 font-bold">×</button>
            </div>
        @endforeach
        <div class="flex items-center justify-between font-semibold"><span>Total</span><span class="text-emerald-700">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
        <button wire:click="save" class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">Simpan & Tambah Stok →</button>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Riwayat Pembelian</h2>
        @forelse ($history as $h)
            <div class="flex justify-between text-sm py-2 border-b border-slate-50 last:border-0"><span class="font-bold">{{ $h->code }}</span><span>Rp {{ number_format($h->total, 0, ',', '.') }}</span></div>
        @empty
            <p class="text-sm text-slate-400 text-center py-4">Belum ada pembelian.</p>
        @endforelse
    </div>
</div>


