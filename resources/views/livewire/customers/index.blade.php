<div>
    <x-page-header title="Pelanggan" description="Kelola pelanggan & riwayat resep toko Anda">
        <a href="{{ route('customers.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Tambah Pelanggan</a>
    </x-page-header>

    <x-search-input model="search" placeholder="Cari nama / nomor HP..." label="Cari pelanggan" />

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($customers as $c)
            <div class="card p-4 hover:border-accent/50 transition">
                <div class="flex justify-between items-start mb-2">
                    <div class="font-semibold text-text-main truncate">{{ $c->name }}</div>
                    <span class="status-badge info">Aktif</span>
                </div>
                <div class="text-[11px] text-text-secondary mb-3">{{ $c->phone ?? '-' }}</div>
                <div class="flex items-center gap-2 pt-3 border-t border-line">
                    <a href="{{ route('customers.show', $c->id) }}" wire:navigate
                        class="text-accent text-xs font-medium hover:text-accent-hover">Lihat detail &rarr;</a>
                    <button wire:click="delete({{ $c->id }})"
                        class="text-error text-xs font-medium hover:text-error/80">Nonaktifkan</button>
                </div>
            </div>
        @empty
            <x-empty-state title="Belum ada pelanggan."
                description="Mulai menambahkan pelanggan pertama Anda untuk mencatat transaksi dan resep."
                actionHref="{{ route('customers.create') }}" actionLabel="Tambah Pelanggan" />
        @endforelse
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>


