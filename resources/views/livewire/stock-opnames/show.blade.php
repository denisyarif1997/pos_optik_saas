<div class="space-y-6">
    <x-page-header title="Detail Stock Opname: {{ $opname->code }}"
                   description="Ringkasan hasil penghitungan fisik stok dan selisih dengan stok sistem">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <a href="{{ route('stock-opnames.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                    <span>&larr;</span> Kembali
                </a>
                @if ($opname->status === 'completed')
                    <a href="{{ route('export.stock-opname') }}?opname_id={{ $opname->id }}" target="_blank" class="btn-outline px-4 py-2 text-sm flex items-center gap-1.5">
                        <x-icon name="download" class="w-4 h-4" /> Ekspor CSV
                    </a>
                @endif
            </div>
        </x-slot:actions>
    </x-page-header>

    <!-- Info opname -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Kode Opname</p>
                <p class="text-lg font-semibold text-text-main">{{ $opname->code }}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Tanggal</p>
                <p class="text-lg font-semibold text-text-main">{{ $opname->conducted_at?->format('d/m/Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Status</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                    {{ $opname->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($opname->status === 'cancelled' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700') }}">
                    {{ ucfirst($opname->status) }}
                </span>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Oleh</p>
                <p class="text-lg font-semibold text-text-main">{{ $opname->conductor?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Selesai</p>
                <p class="text-lg font-semibold text-text-main">{{ $opname->completed_at?->format('d/m/Y H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Total Item</p>
                <p class="text-lg font-semibold text-text-main">{{ $opname->items->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan selisih -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-4">Ringkasan Selisih Stok</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Total Item</p>
                <p class="text-lg font-semibold text-text-main">{{ $opname->items->count() }}</p>
            </div>
            <div class="p-4 rounded-lg bg-emerald-50">
                <p class="text-[11px] uppercase tracking-wider text-emerald-700 font-bold">Tidak Ada Selisih</p>
                <p class="text-lg font-semibold text-emerald-700">{{ $opname->items->where('diff', 0)->count() }}</p>
            </div>
            <div class="p-4 rounded-lg bg-sky-50">
                <p class="text-[11px] uppercase tracking-wider text-sky-700 font-bold">Selisih Lebih</p>
                <p class="text-lg font-semibold text-sky-700">{{ $opname->items->where('diff', '>', 0)->count() }}</p>
            </div>
            <div class="p-4 rounded-lg bg-red-50">
                <p class="text-[11px] uppercase tracking-wider text-red-700 font-bold">Selisih Kurang</p>
                <p class="text-lg font-semibold text-red-700">{{ $opname->items->where('diff', '<', 0)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel detail item -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Produk</th>
                        <th class="py-3 px-4 text-center">SKU</th>
                        <th class="py-3 px-4 text-center">Stok Sistem</th>
                        <th class="py-3 px-4 text-center">Hitung Fisik</th>
                        <th class="py-3 px-4 text-right">Selisih</th>
                        <th class="py-3 px-4 text-right">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($opname->items as $it)
                        @php
                            $diff = (int) ($it->counted_qty ?? 0) - (int) $it->system_qty;
                            $badgeClass = $diff === 0 ? 'bg-emerald-100 text-emerald-700' : ($diff > 0 ? 'bg-sky-50 text-sky-700' : 'bg-red-50 text-red-700');
                            $badgeLabel = $diff === 0 ? 'Cocok' : ($diff > 0 ? 'Lebih' : 'Kurang');
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-semibold text-text-main text-xs sm:text-sm">{{ $it->product?->name ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $it->product?->sku ?? 'Tanpa SKU' }}</div>
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-xs">{{ $it->system_qty }}</td>
                            <td class="py-3 px-4 text-center font-mono text-xs">{{ $it->counted_qty ?? 0 }}</td>
                            <td class="py-3 px-4 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $badgeClass }}">
                                    @if ($diff > 0)+{{ $diff }}@else{{ $diff }}@endif
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $badgeClass }}">
                                    {{ $badgeLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-slate-400">
                                Tidak ada item pada opname ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>