<div>
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Riwayat Stok</h1>
            <p class="text-xs text-slate-500">Riwayat perubahan stok untuk produk lensa</p>
        </div>
        <a href="{{ route('inventory.index') }}" wire:navigate class="ml-auto bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Kelola Stok</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <input type="text" wire:model.live="search" placeholder="Cari nama produk atau SKU..." class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <th class="py-2.5 pr-3">Produk</th>
                <th class="pr-3">SKU</th>
                <th class="pr-3">Stok Saat Ini</th>
                <th class="pr-3">Min Stock</th>
                <th class="text-right pr-3">Status</th>
                <th></th>
            </tr></thead>
            <tbody>
                @forelse ($products as $p)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-2.5 pr-3 font-bold">{{ $p->name }}</td>
                        <td class="pr-3 text-slate-500">{{ $p->sku }}</td>
                        <td class="pr-3 font-bold text-emerald-600">{{ $p->stock }}</td>
                        <td class="pr-3 text-slate-500">{{ $p->min_stock }}</td>
                        <td><span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $p->stock <= $p->min_stock ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $p->stock <= $p->min_stock ? 'Rendah' : 'Aman' }}</span></td>
                        <td class="text-right"><a href="{{ route('inventory.lens-grid', $p->id) }}" wire:navigate class="text-emerald-700 font-bold text-xs">Detail →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-slate-400">Belum ada produk stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>