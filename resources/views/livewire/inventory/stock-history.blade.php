<div>
    <x-page-header title="Riwayat Stok" description="Riwayat perubahan stok untuk produk lensa">
        <x-slot:actions>
            <a href="{{ route('inventory.index') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">Kelola Stok</a>
        </x-slot:actions>
    </x-page-header>

    <x-search-input model="search" placeholder="Cari nama produk atau SKU..." label="Cari produk" />

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">Produk</th>
                        <th class="py-3 px-4 text-left w-32">SKU</th>
                        <th class="py-3 px-4 text-right w-24">Stok Saat Ini</th>
                        <th class="py-3 px-4 text-right w-24">Min Stock</th>
                        <th class="py-3 px-4 text-left w-20">Status</th>
                        <th class="py-3 px-4 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($products as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-text-main">{{ $p->name }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $p->sku }}</td>
                            <td class="py-3 px-4 text-[11px] font-bold text-right {{ $p->stock <= $p->min_stock ? 'text-error' : 'text-success' }}">{{ $p->stock }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary text-right">{{ $p->min_stock }}</td>
                            <td class="py-3 px-4">
                                <x-status-badge :status="$p->stock <= $p->min_stock ? 'Rendah' : 'Aman'" :class="$p->stock <= $p->min_stock ? 'warning' : 'success'" />
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('inventory.lens-grid', $p->id) }}" wire:navigate
                                    class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">Belum ada produk stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>