<div class="space-y-5">
    <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <div>
            <h1 class="text-lg font-bold tracking-tight text-slate-900">Laporan Histori Stok</h1>
            <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi pergerakan stok masuk, keluar, retur, dan penyesuaian toko</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('export.stock-history', ['from' => $from, 'to' => $to, 'type' => $type, 'search' => $search, 'product_id' => $productId]) }}"
               class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg border border-[#e2e8f0] bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition-colors">
                <x-icon name="download" class="w-3.5 h-3.5" />
                Export CSV
            </a>
            <a href="{{ route('inventory.index') }}" wire:navigate
               class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg bg-[#10b981] hover:bg-[#0d9488] text-xs font-semibold text-white transition-colors">
                <x-icon name="layers" class="w-3.5 h-3.5" />
                Kelola Stok
            </a>
        </div>
    </div>

    {{-- Ringkasan KPI Mutasi Stok --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Mutasi</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900 tabular-nums">{{ number_format($summary['count']) }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Catatan pergerakan stok</p>
        </div>
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Stok Masuk</p>
            <p class="mt-2 text-2xl font-extrabold text-emerald-600 tabular-nums">+{{ number_format($summary['in']) }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Pembelian, retur, &amp; tambah stok</p>
        </div>
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Stok Keluar</p>
            <p class="mt-2 text-2xl font-extrabold text-red-600 tabular-nums">-{{ number_format($summary['out']) }}</p>
            <p class="mt-1 text-[11px] text-slate-500">Penjualan &amp; pengurangan stok</p>
        </div>
        <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Selisih Bersih</p>
            <p class="mt-2 text-2xl font-extrabold tabular-nums {{ $summary['net'] >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                {{ $summary['net'] > 0 ? '+' : '' }}{{ number_format($summary['net']) }}
            </p>
            <p class="mt-1 text-[11px] text-slate-500">Net perubahan periode ini</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Cari Mutasi</label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari nama produk, SKU, catatan, atau petugas..."
                       class="input text-xs py-2">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Produk</label>
                <select wire:model.live="productId" class="input text-xs py-2">
                    <option value="">Semua produk</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} {{ $p->sku ? "({$p->sku})" : '' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Tipe Mutasi</label>
                <select wire:model.live="type" class="input text-xs py-2">
                    <option value="">Semua tipe</option>
                    @foreach ($typeOptions as $opt)
                        <option value="{{ $opt }}">
                            {{ match ($opt) {
                                'in' => 'Masuk (In)',
                                'out' => 'Keluar (Out)',
                                'adjustment' => 'Penyesuaian',
                                default => ucfirst($opt),
                            } }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1 min-w-0">
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Periode</label>
                    <div class="flex items-center gap-1">
                        <input type="date" wire:model.live="from" class="input text-xs px-2 py-2">
                        <span class="text-slate-400 text-[10px]">-</span>
                        <input type="date" wire:model.live="to" class="input text-xs px-2 py-2">
                    </div>
                </div>
                <button type="button" wire:click="resetFilter"
                        class="h-[34px] px-2.5 rounded-lg border border-[#e2e8f0] text-xs font-medium text-slate-600 hover:bg-slate-50">
                    Reset
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Histori Stock Movements --}}
    <div wire:loading.class="opacity-60" class="transition-opacity bg-white rounded-[10px] border border-[#e2e8f0] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead class="bg-[#f4f7f9] border-b border-[#e2e8f0] text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left w-10">No</th>
                        <th class="py-3 px-4 text-left">Waktu</th>
                        <th class="py-3 px-4 text-left">Produk</th>
                        <th class="py-3 px-4 text-left">Tipe</th>
                        <th class="py-3 px-4 text-left">Referensi &amp; Catatan</th>
                        <th class="py-3 px-4 text-right">Stok Awal</th>
                        <th class="py-3 px-4 text-right">Perubahan</th>
                        <th class="py-3 px-4 text-right">Stok Akhir</th>
                        <th class="py-3 px-4 text-left">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($movements as $m)
                        @php
                            $isPositive = $m->qty > 0;
                            $badgeClass = $m->type === 'in' || $isPositive
                                ? 'success'
                                : ($m->type === 'out' || $m->qty < 0 ? 'error' : 'warning');
                            $typeLabel = match ($m->type) {
                                'in' => 'Masuk',
                                'out' => 'Keluar',
                                'adjustment' => 'Penyesuaian',
                                default => ucfirst($m->type),
                            };
                        @endphp
                        <tr wire:key="stock-movement-{{ $m->id }}" class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 text-slate-500 tabular-nums">{{ ($movements->firstItem() ?? 0) + $loop->index }}</td>
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap tabular-nums">
                                {{ $m->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-900">{{ $m->product?->name ?? 'Produk dihapus' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $m->product?->sku ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <x-status-badge :status="$typeLabel" :class="$badgeClass" />
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <div class="font-medium text-slate-800">
                                    {{ $m->referenceLabel() }}
                                    @if ($m->reference_id)
                                        <span class="text-slate-400 font-normal">#{{ $m->reference_id }}</span>
                                    @endif
                                </div>
                                @if ($m->notes)
                                    <div class="text-[11px] text-slate-500">{{ $m->notes }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500 tabular-nums">{{ number_format($m->stock_before) }}</td>
                            <td class="py-3 px-4 text-right font-bold tabular-nums {{ $isPositive ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $isPositive ? '+' : '' }}{{ number_format($m->qty) }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900 tabular-nums">{{ number_format($m->stock_after) }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $m->creator?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400 text-sm">
                                Belum ada histori pergerakan stok pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <x-custom-pagination :paginator="$movements" />
    </div>
</div>
