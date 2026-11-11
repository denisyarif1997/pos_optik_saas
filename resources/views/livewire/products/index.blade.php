<div>
    <x-page-header title="Produk & Stok" description="{{ $lowCount }} produk stok menipis • hanya data toko Anda">
    <x-slot:actions>
        <a href="{{ route('products.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Tambah Produk</a>
        <a href="{{ route('export.stock') }}" class="btn-ghost px-4 py-2 text-sm text-xs">⬇ Ekspor CSV</a>
    </x-slot:actions>
</x-page-header>

    <x-search-input model="search" placeholder="Cari nama / SKU / barcode..." label="Cari produk" />

    <div class="flex items-center gap-3 mb-4">
        <select wire:model.live="type" class="input">
            <option value="">Semua tipe</option>
            <option value="frame">Frame</option>
            <option value="lensa">Lensa</option>
            <option value="softlens">Softlens</option>
            <option value="cairan">Cairan</option>
            <option value="aksesori">Aksesori</option>
        </select>
    </div>
    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($products as $p)
            <div class="card p-4 hover:border-accent/50 transition">
                <div class="flex justify-between items-start mb-2">
                    <div class="font-semibold text-text-main truncate">{{ $p->name }}</div>
                    <x-status-badge :status="$p->stock <= $p->min_stock ? 'Menipis' : 'Aman'" :class="$p->stock <= $p->min_stock ? 'warning' : 'success'" />
                </div>
                <div class="text-[11px] text-text-secondary mb-2">{{ ucfirst($p->type) }} • {{ $p->sku ?? '-' }}</div>
                <div class="text-lg font-semibold text-text-main mb-3">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                <div class="flex items-center justify-between text-[11px] mb-3">
                    <span class="text-text-secondary">Stok: {{ $p->stock }}</span>
                    <span class="text-text-secondary">Min: {{ $p->min_stock }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('products.edit', $p->id) }}" wire:navigate
                        class="btn-ghost py-2 text-xs text-center">Ubah</a>
                    <a href="{{ route('products.adjust', $p->id) }}" wire:navigate
                        class="btn-primary py-2 text-xs text-center">Stok</a>
                </div>
            </div>
        @empty
            <x-empty-state title="Belum ada produk."
                description="Tambahkan produk pertama untuk mulai mencatat stok dan transaksi."
                actionHref="{{ route('products.create') }}" actionLabel="Tambah Produk" />
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>


