<div class="space-y-5">
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Inventori Lensa Stok</h1>
            <p class="text-xs text-slate-500">Kelola matriks stok lensa per SPH × CYL untuk produk tipe lensa stok.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3">
        <input type="text" wire:model.live="search" placeholder="Cari nama atau SKU lensa stok..." class="flex-1 rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
    </div>

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($products as $p)
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="min-w-0 flex-1">
                        <div class="font-semibold truncate">{{ $p->name }}</div>
                        <div class="text-xs text-slate-400">{{ $p->sku }} • Lensa Stok</div>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                </div>
                <div class="mt-3 text-sm font-semibold text-emerald-700">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                <div class="mt-5 flex gap-2">
                    <a href="{{ route('inventory.lens-grid', $p->id) }}" wire:navigate class="flex-1 text-center px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-sm font-bold">Buka Grid Stok</a>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-xl border border-dashed border-emerald-200 p-10 text-center text-sm text-slate-400">Belum ada produk lensa stok. Tambahkan produk dengan tipe "Lensa Stok" terlebih dahulu.</div>
        @endforelse
    </div>
    {{ $products->links() }}
</div>
