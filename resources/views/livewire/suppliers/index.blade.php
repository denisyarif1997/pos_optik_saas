<div>
    <x-page-header title="Supplier" description="Distributor frame dan lab lensa">
        <x-slot:actions>
            <a href="{{ route('suppliers.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Tambah Supplier</a>
        </x-slot:actions>
    </x-page-header>

    <x-search-input model="search" placeholder="Cari nama atau nomor HP…" label="Cari supplier" />

    <div wire:loading.class="opacity-60" class="transition-opacity">
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left w-10">No</th>
                            <th class="py-3 px-4 text-left">Nama Supplier</th>
                            <th class="py-3 px-4 text-left w-48">Telepon</th>
                            <th class="py-3 px-4 text-left w-32">Status</th>
                            <th class="py-3 px-4 text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($suppliers as $s)
                            <tr wire:key="supplier-{{ $s->id }}" class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-[11px] text-text-secondary tabular-nums">{{ ($suppliers->firstItem() ?? 0) + $loop->index }}</td>
                                <td class="py-3 px-4 font-medium text-text-main truncate max-w-xs">{{ $s->name }}</td>
                                <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $s->phone ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="status-badge {{ $s->is_active ? 'success' : 'info' }}">{{ $s->is_active ? 'Aktif' : 'NonAktif' }}</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button wire:click="toggle({{ $s->id }})"
                                            class="text-[11px] font-semibold px-2 py-1 rounded {{ $s->is_active ? 'text-emerald-dark bg-emerald-bg hover:text-emerald' : 'text-slate-500 bg-slate-50 hover:text-slate-700' }}">
                                        {{ $s->is_active ? 'NonAktifkan' : 'Aktifkan' }}
                                    </button>
                                    <a href="{{ route('suppliers.edit', $s->id) }}" wire:navigate
                                       class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-sm">Belum ada supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <x-custom-pagination :paginator="$suppliers" />
    </div>
</div>