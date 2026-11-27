<div>
    <x-page-header title="Produk & Stok" description="{{ $lowCount }} produk stok menipis • hanya data toko Anda">
        <x-slot:actions>
            <a href="{{ route('products.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Tambah Produk</a>
            <a href="{{ route('products.import') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm text-xs border">⬇ Impor CSV</a>
            <a href="{{ route('export.stock') }}" class="btn-ghost px-4 py-2 text-sm text-xs">⬇ Ekspor CSV</a>
        </x-slot:actions>
    </x-page-header>

    <x-search-input model="search" placeholder="Cari nama / SKU / barcode..." label="Cari produk" />

    <div class="flex items-center gap-3 mb-4">
        <select wire:model.live="type" class="input">
            <option value="">Semua tipe</option>
            <option value="FRAME">Frame</option>
            <option value="STOCK_LENS">Lensa</option>
            <option value="RX_LENS">Lensa RX</option>
            <!-- <option value="cairan">Cairan</option> -->
            <!-- <option value="aksesori">Aksesori</option> -->
        </select>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left w-8">No.</th>
                        <th class="py-3 px-4 text-left">Nama Produk</th>
                        <th class="py-3 px-4 text-left w-48">SKU / Barcode</th>
                        <th class="py-3 px-4 text-left w-32">Tipe</th>
                        <th class="py-3 px-4 text-right w-24">Harga Jual</th>
                        <th class="py-3 px-4 text-right w-20">Stok</th>
                        <th class="py-3 px-4 text-right w-20">Min Stok</th>
                        <th class="py-3 px-4 text-left w-20">Status</th>
                        <th class="py-3 px-4 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($products as $p)
                        <?php $no = (($products->currentPage() - 1) * $products->perPage()) + $loop->iteration; ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $no }}</td>
                            <td class="py-3 px-4 font-medium text-text-main truncate max-w-xs">{{ $p->name }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $p->sku ?? '-' }} / {{ $p->barcode ?? '-' }}</td>
                            <td class="py-3 px-4 text-[11px] font-medium text-text-main capitalize">{{ $p->type }}</td>
                            <td class="py-3 px-4 text-[11px] font-semibold text-text-main text-right">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-[11px] font-bold text-right {{ $p->stock <= $p->min_stock ? 'text-error' : 'text-success' }}">{{ $p->stock }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary text-right">{{ $p->min_stock }}</td>
                            <td class="py-3 px-4">
                                <x-status-badge :status="$p->stock <= $p->min_stock ? 'Menipis' : 'Aman'" :class="$p->stock <= $p->min_stock ? 'warning' : 'success'" />
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('products.edit', $p->id) }}" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald">Ubah</a>
                                    <a href="{{ route('products.adjust', $p->id) }}" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Stok</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400 text-sm">Belum ada produk.</td>
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


