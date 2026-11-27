<div>
    <x-page-header title="Inventori Lensa Stok" description="Kelola matriks stok lensa per SPH × CYL untuk produk tipe lensa stok">
        <x-slot:actions>
            <a href="{{ route('products.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Tambah Produk</a>
        </x-slot:actions>
    </x-page-header>

    <x-search-input model="search" placeholder="Cari nama atau SKU lensa stok..." label="Cari produk" />

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left w-10">No</th>
                        <th class="py-3 px-4 text-left w-40">Nama Produk</th>
                        <th class="py-3 px-4 text-left w-32">SKU</th>
                        <th class="py-3 px-4 text-right w-24">Harga Jual</th>
                        <th class="py-3 px-4 text-left w-32">Tipe</th>
                        <th class="py-3 px-4 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($products as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 text-[11px] text-text-secondary tabular-nums">{{ ($products->firstItem() ?? 0) + $loop->index }}</td>
                            <td class="py-3 px-4 font-medium text-text-main">{{ $p->name }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $p->sku ?? '-' }}</td>
                            <td class="py-3 px-4 text-[11px] font-semibold text-text-main text-right">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-[11px] font-medium text-text-main capitalize">{{ $p->type }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('inventory.lens-grid', $p->id) }}" wire:navigate
                                    class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Grid Stok</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">Belum ada produk lensa stok. Tambahkan produk dengan tipe "Lensa Stok" terlebih dahulu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <x-custom-pagination :paginator="$products" />
    </div>
</div>
