@php
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $presets = ['today' => 'Hari ini', '7d' => '7 hari', '30d' => '30 hari', 'all' => 'Semua'];
    $badgeClass = fn ($st) => match ($st) {
        'lunas' => 'success',
        'dp' => 'info',
        default => 'warning',
    };
@endphp

<div>
    <x-page-header title="Riwayat Transaksi" description="Semua penjualan toko Anda">
        <x-slot:actions>
            <a href="{{ route('pos.cashier') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Transaksi Baru</a>
        </x-slot:actions>
    </x-page-header>

    {{-- Ringkasan (seluruh hasil filter) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Transaksi</p>
            <p class="text-lg font-bold text-emerald-800">{{ number_format($summary['count']) }}</p>
            <p class="text-[11px] text-emerald-600">{{ $summary['lunas'] }} lunas</p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Pendapatan</p>
            <p class="text-lg font-bold text-emerald-800">{{ $rp($summary['revenue']) }}</p>
            <p class="text-[11px] text-emerald-600">Diskon {{ $rp($summary['discount']) }} • Retur {{ $rp($summary['refund']) }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Sudah Dibayar</p>
            <p class="text-lg font-bold text-emerald-800">{{ $rp($summary['paid']) }}</p>
        </div>
        <div class="{{ $summary['outstanding'] > 0 ? 'bg-red-50 border-red-100' : 'bg-emerald-50 border-emerald-100' }} rounded-xl p-3 border">
            <p class="text-xs {{ $summary['outstanding'] > 0 ? 'text-red-600' : 'text-emerald-600' }} font-medium">Sisa Tagihan</p>
            <p class="text-lg font-bold {{ $summary['outstanding'] > 0 ? 'text-red-700' : 'text-emerald-800' }}">{{ $rp($summary['outstanding']) }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3 mb-4">
        <x-search-input model="search" placeholder="Cari kode atau nama pelanggan…" label="Cari transaksi" />

        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="paymentStatus" class="input">
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

    <div wire:loading.class="opacity-60" class="transition-opacity">
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left w-40">Transaksi</th>
                            <th class="py-3 px-4 text-left">Pelanggan</th>
                            <th class="py-3 px-4 text-right w-32">Total</th>
                            <th class="py-3 px-4 text-right w-32">Dibayar</th>
                            <th class="py-3 px-4 text-right w-32">Sisa</th>
                            <th class="py-3 px-4 text-left w-32">Kasir</th>
                            <th class="py-3 px-4 text-left w-20">Status</th>
                            <th class="py-3 px-4 text-right w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($sales as $s)
                            @php
                                $refund = (float) $s->refund_total;
                                $total = max(0, (float) $s->total - $refund);
                                $paid = max(0, (float) $s->paid_total - $refund);
                                $sisa = max(0, $total - $paid);
                                $returnStatus = $refund > 0
                                    ? ($refund >= (float) $s->total ? 'Diretur' : 'Retur sebagian')
                                    : null;
                            @endphp
                            <tr wire:key="sale-{{ $s->id }}" class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-text-main">{{ $s->code }}</div>
                                    <p class="text-[11px] text-text-secondary">{{ $s->sold_at?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                                </td>
                                <td class="py-3 px-4 font-medium text-text-main">{{ $s->customer?->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-[11px] font-semibold text-text-main text-right">{{ $rp($total) }}</td>
                                <td class="py-3 px-4 text-[11px] text-right {{ $sisa <= 0 ? 'text-success' : 'text-error' }}">{{ $rp($paid) }}</td>
                                <td class="py-3 px-4 text-[11px] text-right {{ $sisa <= 0 ? 'text-slate-400' : 'text-error' }}">{{ $sisa > 0 ? $rp($sisa) : '-' }}</td>
                                <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $s->cashier?->name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <x-status-badge :status="ucfirst($s->payment_status)" :class="$badgeClass($s->payment_status)" />
                                        @if ($returnStatus)
                                            <x-status-badge :status="$returnStatus" :class="$refund >= (float) $s->total ? 'error' : 'warning'" />
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('sales.show', $s->id) }}" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400 text-sm">Tidak ada transaksi pada filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>