@php
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $presets = ['today' => 'Hari ini', '7d' => '7 hari', '30d' => '30 hari', 'all' => 'Semua'];
    $badge = fn ($st) => match ($st) {
        'lunas' => 'bg-emerald-100 text-emerald-700',
        'dp' => 'bg-blue-100 text-blue-700',
        default => 'bg-amber-100 text-amber-700',
    };
@endphp

<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Riwayat Transaksi</h1>
            <p class="text-xs text-slate-500">Semua penjualan tokomu</p>
        </div>
        <a href="{{ route('pos.cashier') }}" wire:navigate
           class="ml-auto bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">
            + Transaksi Baru
        </a>
    </div>

    {{-- Ringkasan (seluruh hasil filter) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Transaksi</p>
            <p class="text-lg font-bold text-emerald-800">{{ number_format($summary['count']) }}</p>
            <p class="text-[11px] text-emerald-600">{{ $summary['lunas'] }} lunas</p>
        </div>
        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Pendapatan</p>
            <p class="text-lg font-bold text-emerald-800">{{ $rp($summary['revenue']) }}</p>
            <p class="text-[11px] text-emerald-600">Diskon {{ $rp($summary['discount']) }}</p>
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
    <div class="bg-white rounded-xl border border-slate-200 p-3 space-y-3">
        <div class="flex flex-wrap gap-2">
            <input type="search" wire:model.live.debounce.300ms="search"
                   placeholder="Cari kode atau nama pelanggan…"
                   class="flex-1 min-w-[220px] rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-400 outline-none">
            <select wire:model.live="paymentStatus"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-400 outline-none">
                <option value="">Semua status</option>
                @foreach ($statusOptions as $opt)
                    <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @foreach ($presets as $key => $label)
                <button type="button" wire:click="setRange('{{ $key }}')"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors
                               {{ $activeRange === $key ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </button>
            @endforeach

            <div class="flex items-center gap-1.5 ml-auto">
                <input type="date" wire:model.live="from"
                       class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs focus:ring-2 focus:ring-emerald-400 outline-none">
                <span class="text-slate-400 text-xs">s/d</span>
                <input type="date" wire:model.live="to"
                       class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs focus:ring-2 focus:ring-emerald-400 outline-none">
                <button type="button" wire:click="resetFilter"
                        class="text-xs text-slate-500 hover:text-slate-700 underline ml-1">Reset</button>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity">
        {{-- Desktop: tabel --}}
        <div class="hidden md:block bg-white rounded-xl border border-slate-200 p-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="py-2.5 pr-3">Transaksi</th>
                        <th class="py-2.5 pr-3">Pelanggan</th>
                        <th class="py-2.5 pr-3 text-right">Total</th>
                        <th class="py-2.5 pr-3 text-right">Dibayar</th>
                        <th class="py-2.5 pr-3 text-right">Sisa</th>
                        <th class="py-2.5 pr-3">Kasir</th>
                        <th class="py-2.5 pr-3">Status</th>
                        <th class="py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $s)
                        @php
                            $total = (float) $s->total;
                            $paid = (float) $s->paid_total;
                            $disc = (float) $s->discount_total;
                            $sisa = max(0, $total - $paid);
                        @endphp
                        <tr wire:key="sale-{{ $s->id }}" class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                            <td class="py-2.5 pr-3">
                                <p class="font-bold">{{ $s->code }}</p>
                                <p class="text-[11px] text-slate-400">{{ $s->sold_at?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                            </td>
                            <td class="py-2.5 pr-3 font-medium text-slate-800">{{ $s->customer?->name ?? '-' }}</td>
                            <td class="py-2.5 pr-3 text-right">
                                <p class="font-bold">{{ $rp($total) }}</p>
                                @if ($disc > 0)
                                    <p class="text-[11px] text-slate-400">Diskon {{ $rp($disc) }}</p>
                                @endif
                            </td>
                            <td class="py-2.5 pr-3 text-right font-semibold {{ $sisa <= 0 ? 'text-emerald-700' : 'text-amber-700' }}">{{ $rp($paid) }}</td>
                            <td class="py-2.5 pr-3 text-right font-bold {{ $sisa <= 0 ? 'text-slate-300' : 'text-red-600' }}">{{ $sisa > 0 ? $rp($sisa) : '-' }}</td>
                            <td class="py-2.5 pr-3 text-slate-500 text-xs">{{ $s->cashier?->name ?? '-' }}</td>
                            <td class="py-2.5 pr-3">
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $badge($s->payment_status) }}">{{ ucfirst($s->payment_status) }}</span>
                            </td>
                            <td class="py-2.5 text-center">
                                <a href="{{ route('sales.show', $s->id) }}" wire:navigate
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-black text-white text-xs font-semibold transition-colors">
                                    <x-icon name="eye" class="w-3.5 h-3.5" />
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada transaksi pada filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: kartu --}}
        <div class="md:hidden space-y-2">
            @forelse ($sales as $s)
                @php
                    $total = (float) $s->total;
                    $paid = (float) $s->paid_total;
                    $sisa = max(0, $total - $paid);
                @endphp
                <a wire:key="sale-m-{{ $s->id }}" href="{{ route('sales.show', $s->id) }}" wire:navigate
                   class="block bg-white rounded-xl border border-slate-200 p-3 active:bg-slate-50">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-bold text-sm">{{ $s->code }}</p>
                            <p class="text-[11px] text-slate-400">{{ $s->sold_at?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                        </div>
                        <span class="shrink-0 text-[11px] font-bold px-2.5 py-1 rounded-full {{ $badge($s->payment_status) }}">{{ ucfirst($s->payment_status) }}</span>
                    </div>
                    <p class="text-sm text-slate-700 mt-1.5 truncate">{{ $s->customer?->name ?? '-' }}</p>
                    <div class="flex items-end justify-between mt-2 pt-2 border-t border-slate-100">
                        <div>
                            <p class="text-[11px] text-slate-400">Total</p>
                            <p class="font-bold text-sm">{{ $rp($total) }}</p>
                        </div>
                        @if ($sisa > 0)
                            <div class="text-right">
                                <p class="text-[11px] text-slate-400">Sisa</p>
                                <p class="font-bold text-sm text-red-600">{{ $rp($sisa) }}</p>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-xl border border-slate-200 py-8 text-center text-slate-400 text-sm">
                    Tidak ada transaksi pada filter ini.
                </div>
            @endforelse
        </div>
    </div>

    <div>{{ $sales->links() }}</div>
</div>