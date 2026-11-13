<div>
    <x-page-header title="Stock Opname" description="Kelola pengajuan stok opname toko Anda">
        <x-slot:actions>
            <button wire:click="create" class="btn-primary px-4 py-2 text-sm">+ Mulai Opname Baru</button>
        </x-slot:actions>
    </x-page-header>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left w-32">Kode</th>
                        <th class="py-3 px-4 text-left w-32">Tanggal</th>
                        <th class="py-3 px-4 text-left w-20">Status</th>
                        <th class="py-3 px-4 text-left">Oleh</th>
                        <th class="py-3 px-4 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($opnames as $o)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-text-main">{{ $o->code }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $o->conducted_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $opBadge = match ($o->status) {
                                        'completed' => 'success',
                                        'cancelled' => 'error',
                                        default => 'warning',
                                    };
                                @endphp
                                <x-status-badge :status="ucfirst($o->status)" :class="$opBadge" />
                            </td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $o->conductor?->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-right">
                                @if ($o->status === 'draft')
                                    <a href="{{ route('stock-opnames.count', $o->id) }}" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Hitung</a>
                                @else
                                    <a href="{{ route('stock-opnames.show', $o->id) }}" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">Belum ada opname.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>