<div class="space-y-6">
    <x-page-header title="Hitung Fisik Stock Opname: {{ $opname->code }}"
                   description="Masukkan jumlah stok fisik riil di rak untuk dibandingkan dengan stok sistem">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <button wire:click="cancel"
                        wire:confirm="Batalkan sesi stock opname ini?"
                        class="btn-ghost px-4 py-2 text-sm">
                    Batal
                </button>
                <button wire:click="finalize"
                        wire:confirm="Finalisasi hasil opname ini? Stok produk akan otomatis disesuaikan dengan selisih fisik."
                        class="btn-primary px-5 py-2 text-sm font-semibold flex items-center gap-2">
                    <x-icon name="check" class="w-4 h-4" />
                    Finalisasi &amp; Sesuaikan Stok
                </button>
            </div>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-5 sm:p-6 bg-surface border border-line shadow-card rounded-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-line">
            <div>
                <h2 class="text-sm font-semibold text-text-main">Lembar Penghitungan Fisik Produk</h2>
                <p class="text-xs text-text-secondary mt-0.5">Isi kolom <strong>Hitung Fisik</strong> sesuai hasil pengecekan di etalase/gudang</p>
            </div>
            <span class="status-badge warning">Sesi Opname Terbuka</span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-line">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-canvas border-b border-line text-[11px] font-bold text-text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Produk</th>
                        <th class="py-3 px-4 text-center">Stok Sistem</th>
                        <th class="py-3 px-4 text-center">Hitung Fisik Riil</th>
                        <th class="py-3 px-4 text-right">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line bg-surface">
                    @forelse ($opname->items as $it)
                        @php
                            $physicalCount = $counts[$it->id] ?? $it->system_qty;
                            $diff = (int)$physicalCount - (int)$it->system_qty;
                        @endphp
                        <tr class="hover:bg-canvas/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-semibold text-text-main text-xs sm:text-sm">{{ $it->product?->name ?? '-' }}</div>
                                <div class="text-[11px] text-text-secondary">{{ $it->product?->sku ?? 'Tanpa SKU' }}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-mono font-semibold text-xs px-2.5 py-1 rounded-lg bg-canvas border border-line">
                                    {{ $it->system_qty }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <input type="number"
                                       wire:model.live="counts.{{ $it->id }}"
                                       class="w-24 text-center font-mono font-bold text-sm input py-1.5 px-2">
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if ($diff === 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-bg text-emerald-dark">
                                        Cocok (0)
                                    </span>
                                @elseif ($diff > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700">
                                        +{{ $diff }} (Lebih)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600 font-bold">
                                        {{ $diff }} (Kurang)
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-text-secondary">
                                Tidak ada item pada sesi opname ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>