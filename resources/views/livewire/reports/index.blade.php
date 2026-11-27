<div class="space-y-6">

    {{-- Header & filter --}}
    <div class="bg-white rounded-2xl border border-emerald-100/70 shadow-sm p-5 flex flex-col lg:flex-row gap-4 lg:items-end">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <x-icon name="banknotes" class="w-6 h-6" />
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">Laporan Toko</h1>
                <p class="text-xs text-slate-500 mt-0.5">Hanya data tokomu • mencakup penjualan tunai &amp; pesanan kacamata selesai</p>
            </div>
        </div>
        <div class="lg:ml-auto flex flex-wrap items-end gap-3">
            <label class="text-xs font-medium text-slate-600">Dari
                <input type="date" wire:model.live="from" class="mt-1.5 block rounded-xl bg-slate-50 border border-emerald-100 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 transition">
            </label>
            <label class="text-xs font-medium text-slate-600">Sampai
                <input type="date" wire:model.live="to" class="mt-1.5 block rounded-xl bg-slate-50 border border-emerald-100 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 transition">
            </label>
            <a href="{{ route('export.sales', ['from' => $from, 'to' => $to]) }}" class="self-end text-xs font-semibold px-4 py-2.5 rounded-xl bg-emerald-500 text-white shadow-sm shadow-emerald-200 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 transition">⬇ CSV</a>
        </div>
    </div>

    {{-- Ringkasan angka --}}
    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>Omzet penjualan
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-emerald-700">Rp {{ number_format($saleRevenue, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-400 mt-1.5">{{ $saleCount }} transaksi • retur Rp {{ number_format($refundTotal, 0, ',', '.') }}</div>
        </div>
        <!-- <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>Omzet optical
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-blue-600">Rp {{ number_format($opticalRevenue, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-400 mt-1.5">{{ $opticalCount }} pesanan</div>
        </div> -->
        <div class="col-span-2 xl:col-span-1 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-md shadow-emerald-200 p-5 text-white">
            <div class="text-xs font-medium text-emerald-100">Total omzet</div>
            <div class="mt-2 text-xl font-bold tabular-nums">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
            <div class="text-xs text-emerald-100 mt-1.5">{{ $count }} transaksi</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>Kas bersih
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-slate-900">Rp {{ number_format($collected, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>Piutang
            </div>
            <div class="mt-2 text-xl font-bold tabular-nums text-amber-600">Rp {{ number_format($receivable, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Laba / Rugi + Produk terlaris --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <h2 class="font-semibold text-slate-900 mb-4">Laba / Rugi (Periode)</h2>
            <div class="text-sm divide-y divide-slate-100">
                <div class="flex justify-between py-2.5"><span class="text-slate-600">Omzet</span><span class="font-semibold tabular-nums text-slate-900">Rp {{ number_format($revenue, 0, ',', '.') }}</span></div>
                <div class="flex justify-between py-2.5"><span class="text-slate-600">HPP (COGS)</span><span class="font-semibold tabular-nums text-red-600">Rp {{ number_format($cogs, 0, ',', '.') }}</span></div>
                <div class="flex justify-between py-2.5"><span class="text-slate-600">Laba kotor</span><span class="font-semibold tabular-nums text-emerald-700">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span></div>
                <div class="flex justify-between py-2.5"><span class="text-slate-600">Pengeluaran</span><span class="font-semibold tabular-nums text-red-600">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</span></div>
            </div>
            <div class="mt-3 flex items-center justify-between rounded-xl px-4 py-3.5 {{ $netProfit >= 0 ? 'bg-emerald-50 border border-emerald-100' : 'bg-red-50 border border-red-100' }}">
                <span class="font-semibold text-slate-800">Laba bersih</span>
                <span class="font-bold text-lg tabular-nums {{ $netProfit >= 0 ? 'text-emerald-700' : 'text-red-600' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
            <h2 class="font-semibold text-slate-900 mb-4">Produk terlaris</h2>
            @forelse ($topProducts as $t)
                <div wire:key="top-product-{{ $t->product_id }}" class="flex items-center gap-3 text-sm py-2.5 border-b border-slate-100 last:border-0">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold flex items-center justify-center shrink-0">{{ $loop->iteration }}</span>
                    <span class="font-semibold text-slate-800 min-w-0 truncate">{{ $t->product?->name ?? '-' }}</span>
                    <span class="text-xs text-slate-400 font-normal shrink-0">× {{ $t->qty }}</span>
                    <span class="ml-auto font-medium tabular-nums text-slate-700">Rp {{ number_format($t->omzet, 0, ',', '.') }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400 text-center py-8">Belum ada penjualan pada periode ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Metode Pembayaran -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
        <h2 class="font-semibold text-slate-900 mb-4">Metode pembayaran</h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            @php
                $methodLabels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'card' => 'Kartu'];
            @endphp
            @foreach ($methodLabels as $method => $label)
                <div wire:key="payment-method-{{ $method }}" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs text-slate-500 font-medium">{{ $label }}</div>
                        <div class="mt-1 text-base font-bold tabular-nums text-slate-900 truncate">Rp {{ number_format($paymentMethods[$method] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                        @switch($method)
                            @case('cash') bg-emerald-100 text-emerald-600 @break
                            @case('transfer') bg-blue-100 text-blue-600 @break
                            @case('qris') bg-purple-100 text-purple-600 @break
                            @default bg-slate-100 text-slate-500 @break
                        @endswitch">
                        @switch($method)
                            @case('cash') <x-icon name="banknotes" class="w-5 h-5" /> @break
                            @case('transfer') <x-icon name="refresh-cw" class="w-5 h-5" /> @break
                            @case('qris') <x-icon name="qrcode" class="w-5 h-5" /> @break
                            @default <x-icon name="credit-card" class="w-5 h-5" /> @break
                        @endswitch
                    </div>
                </div>
            @endforeach
            @if (count($paymentMethods) > 0)
                <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50 flex items-center justify-between">
                    <div class="min-w-0">
                        <div class="text-xs text-emerald-700 font-medium">Total</div>
                        <div class="mt-1 text-base font-bold tabular-nums text-emerald-800 truncate">Rp {{ number_format(array_sum($paymentMethods), 0, ',', '.') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tren Harian -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
        <h2 class="font-semibold text-slate-900 mb-4">Tren harian</h2>
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500">
                    <tr>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-right">Penjualan</th>
                        <!-- <th class="py-3 px-4 text-right">Optical</th> -->
                        <th class="py-3 px-4 text-right">Total</th>
                        <th class="py-3 px-4 text-center">Jumlah transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dailyTrend as $day)
                        <tr wire:key="daily-trend-{{ $day['date'] }}" class="hover:bg-emerald-50/40 transition-colors">
                            <td class="py-3 px-4 font-medium text-slate-800">{{ \Carbon\Carbon::parse($day['date'])->format('d M') }}</td>
                            <td class="py-3 px-4 text-right tabular-nums text-slate-600">Rp {{ number_format($day['sale'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right tabular-nums text-blue-600">Rp {{ number_format($day['optical'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right tabular-nums font-semibold text-slate-900">Rp {{ number_format($day['sale'] + $day['optical'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[1.75rem] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">{{ $day['count'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-sm text-slate-400">Belum ada data pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaksi Terkini -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-900">Transaksi terkini</h2>
            <a href="{{ route('export.transactions', ['from' => $from, 'to' => $to]) }}"
               class="text-xs font-semibold px-3.5 py-2 rounded-xl bg-emerald-500 text-white shadow-sm shadow-emerald-200 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 transition">
                ⬇ Export CSV
            </a>
        </div>
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500">
                    <tr>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Jenis</th>
                        <th class="py-3 px-4 text-left">Kode</th>
                        <th class="py-3 px-4 text-left">Pelanggan</th>
                        <th class="py-3 px-4 text-left">Nama kas</th>
                        <th class="py-3 px-4 text-left">Diproses oleh</th>
                        <th class="py-3 px-4 text-right">Total</th>
                        <th class="py-3 px-4 text-right">Dibayar</th>
                        <th class="py-3 px-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentTransactions as $t)
                        <tr wire:key="recent-transaction-{{ $t['type'] }}-{{ $t['code'] }}" class="hover:bg-emerald-50/40 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap text-slate-600">{{ \Carbon\Carbon::parse($t['date'])->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                    {{ $t['type'] === 'Penjualan' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $t['type'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-semibold whitespace-nowrap text-slate-900">{{ $t['code'] }}</td>
                            <td class="py-3 px-4 text-slate-800">{{ $t['customer'] }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $t['cash_name'] }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $t['processed_by'] }}</td>
                            <td class="py-3 px-4 text-right tabular-nums font-medium text-slate-900">Rp {{ number_format($t['total'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right tabular-nums text-slate-600">Rp {{ number_format($t['paid'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                    @switch($t['status'])
                                        @case('lunas') bg-emerald-100 text-emerald-700 @break
                                        @case('dp') bg-amber-100 text-amber-700 @break
                                        @case('COMPLETED') bg-emerald-100 text-emerald-700 @break
                                        @default bg-slate-100 text-slate-500 @break
                                    @endswitch">
                                    {{ $t['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 text-center text-sm text-slate-400">Belum ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>