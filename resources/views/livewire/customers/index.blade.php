<div>
    <x-page-header title="Pelanggan" description="Kelola pelanggan & riwayat resep toko Anda">
    <x-slot:actions>
        <a href="{{ route('customers.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Tambah Pelanggan</a>
    </x-slot:actions>
    </x-page-header>
    
    <x-search-input model="search" placeholder="Cari nama / nomor HP..." label="Cari pelanggan" />

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left w-10">No</th>
                        <th class="py-3 px-4 text-left">Nama Pelanggan</th>
                        <th class="py-3 px-4 text-left w-32">Nomor HP</th>
                        <th class="py-3 px-4 text-left w-20">Status</th>
                        <th class="py-3 px-4 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($customers as $c)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 text-[11px] text-text-secondary tabular-nums">{{ ($customers->firstItem() ?? 0) + $loop->index }}</td>
                            <td class="py-3 px-4 font-medium text-text-main">{{ $c->name }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $c->phone ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <x-status-badge :status="'Aktif'" class="info" />
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('customers.show', $c->id) }}" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                                    <button wire:click="delete({{ $c->id }})"
                                        class="text-[11px] font-semibold text-error hover:opacity-70">Nonaktifkan</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-sm">Belum ada pelanggan. Mulai menambahkan pelanggan pertama Anda untuk mencatat transaksi dan resep.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <x-custom-pagination :paginator="$customers" />
    </div>
</div>


