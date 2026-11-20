@php
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $presets = ['today' => 'Hari ini', '7d' => '7 hari', '30d' => '30 hari', 'all' => 'Semua'];
    $badgeClass = fn ($st) => match ($st) {
        'approved' => 'success',
        default => 'info',
    };
@endphp

<div>
    <x-page-header title="Retur Penjualan" description="Stok kembali dan tercatat">
        <x-slot:actions>
            <a href="{{ route('pos.cashier') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Transaksi Baru</a>
        </x-slot:actions>
    </x-page-header>

    {{-- Ringkasan (seluruh hasil filter) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Retur</p>
            <p class="text-lg font-bold text-emerald-800">{{ number_format($summary['count']) }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Total Refund</p>
            <p class="text-lg font-bold text-emerald-800">{{ $rp($summary['refund']) }}</p>
        </div>
    </div>

    {{-- Form Buat Retur --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4 mb-4">
        <h1 class="font-semibold text-text-main">Buat Retur <span class="text-xs font-normal text-text-secondary">• pilih nota, produk, dan qty</span></h1>
        <div class="flex gap-2">
            <input type="text" wire:model="code" placeholder="Kode nota, cth JUAL-..." class="flex-1 rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button wire:click="findSale" class="bg-emerald-500 text-white text-sm font-bold px-4 rounded-lg">Cari</button>
        </div>
        @error('code') <p class="text-xs text-error">{{ $message }}</p> @enderror
        @if ($sale)
            <div class="rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 text-sm">
                <b>{{ $sale->code }}</b> • Rp {{ number_format($sale->total, 0, ',', '.') }}
                <div class="mt-2 space-y-1">@foreach ($sale->items as $it)<div>• {{ $it->product?->name }} × {{ $it->qty }}</div>@endforeach</div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <select wire:model="product_id" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5 text-sm">
                    <option value="">— Produk —</option>
                    @foreach ($sale->items as $it)<option value="{{ $it->product_id }}">{{ $it->product?->name }}</option>@endforeach
                </select>
                <input type="number" min="1" wire:model="qty" class="rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2.5" placeholder="Qty">
            </div>
            <input type="text" wire:model="reason" placeholder="Alasan retur" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button wire:click="save" class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">Simpan Retur →</button>
        @endif
    </div>

    {{-- Filter datatable --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3 mb-4">
        <x-search-input model="search" placeholder="Cari kode nota…" label="Cari retur" />

        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="status" class="input">
                <option value="">Semua status</option>
                @foreach ($statusOptions as $opt)
                    <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                @endforeach
            </select>

            <div class="flex items-center gap-1.5">
                @foreach ($presets as $key => $label)
                    <button type="button" wire:click="setRange('{{ $key }}')"
                            class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors
                                   {{ $activeRange === $key ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="flex items-center gap-1.5">
                <input type="date" wire:model.live="from"
                       class="input text-xs px-2.5 py-1.5">
                <span class="text-slate-400 text-[10px]">s/d</span>
                <input type="date" wire:model.live="to"
                       class="input text-xs px-2.5 py-1.5">
                <button type="button" wire:click="resetFilter"
                        class="text-[11px] text-slate-500 hover:text-slate-700 underline ml-1">Reset</button>
            </div>
        </div>
    </div>

    {{-- Tabel datatable --}}
    <div wire:loading.class="opacity-60" class="transition-opacity">
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left w-40">Transaksi</th>
                            <th class="py-3 px-4 text-left">Pelanggan</th>
                            <th class="py-3 px-4 text-left">Produk</th>
                            <th class="py-3 px-4 text-right w-24">Qty</th>
                            <th class="py-3 px-4 text-right w-32">Refund</th>
                            <th class="py-3 px-4 text-left">Alasan</th>
                            <th class="py-3 px-4 text-left w-20">Status</th>
                            <th class="py-3 px-4 text-left w-32">Tanggal</th>
                            <th class="py-3 px-4 text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($returns as $r)
                            @php
                                $item = $r->sale->items->first(fn ($i) => $i->product_id === $r->product_id);
                                $productName = $item?->product?->name ?? '-';
                                $badge = $badgeClass($r->status ?? 'approved');
                            @endphp
                            <tr wire:key="return-{{ $r->id }}" class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-text-main">{{ $r->sale?->code ?? '-' }}</div>
                                    <p class="text-[11px] text-text-secondary">{{ $r->created_at->translatedFormat('d M Y, H:i') }}</p>
                                </td>
                                <td class="py-3 px-4 text-[11px] text-text-main">{{ $r->sale->customer?->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-[11px] text-text-main">{{ $productName }}</td>
                                <td class="py-3 px-4 text-[11px] text-text-main text-right">{{ $r->qty }}</td>
                                <td class="py-3 px-4 text-[11px] font-semibold text-text-main text-right">{{ $rp($r->refund_amount) }}</td>
                                <td class="py-3 px-4 text-[11px] text-text-main">{{ $r->reason ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <x-status-badge :status="ucfirst($r->status ?? 'approved')" :class="$badge" />
                                </td>
                                <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $r->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-right">
                                    @if ($r->sale)
                                        <a href="{{ route('sales.show', $r->sale->id) }}" wire:navigate
                                            class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-400 text-sm">Tidak ada retur pada filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $returns->links() }}
    </div>
</div>