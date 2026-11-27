<div class="space-y-5"
     x-data="{
         seriesVisible: {
             lenses: true,
             frames: true,
             returns: true
         },
         hoveredIndex: null,
         toggleSeries(key) {
             this.seriesVisible[key] = !this.seriesVisible[key];
         }
     }">

    <!-- ================= SECTION HEADER & COMPACT FILTERS ================= -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold tracking-tight text-slate-900">Ringkasan Operasional Optik</h2>
            <p class="text-xs text-slate-500 mt-0.5 tabular-nums">
                Diperbarui: {{ now()->translatedFormat('d M Y, H:i') }} WIB &bull; {{ $store->name }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="period" aria-label="Filter Periode"
                    class="h-9 rounded-lg border border-[#e2e8f0] bg-white px-3 text-xs font-medium text-slate-700 focus:border-[#10b981] focus:outline-none">
                <option value="7d">7 Hari Terakhir</option>
                <option value="30d">30 Hari Terakhir</option>
                <option value="month">Bulan Berjalan</option>
                <option value="year">Tahun Ini</option>
            </select>

            <select wire:model.live="selectedStoreId" aria-label="Filter Cabang / Outlet"
                    class="h-9 rounded-lg border border-[#e2e8f0] bg-white px-3 text-xs font-medium text-slate-700 focus:border-[#10b981] focus:outline-none">
                @foreach ($stores as $st)
                    <option value="{{ $st->id }}">{{ $st->name }}</option>
                @endforeach
            </select>

            <a href="{{ route('pos.cashier') }}"
               class="h-9 inline-flex items-center gap-1.5 rounded-lg bg-[#10b981] hover:bg-[#0d9488] px-3.5 text-xs font-semibold text-white transition-colors">
                <x-icon name="plus" class="w-3.5 h-3.5" />
                Transaksi Baru
            </a>
        </div>
    </div>

    <!-- ================= TOP KPI SUMMARY ROW (4 EQUAL-WIDTH CARDS) ================= -->
    <section aria-label="Ringkasan KPI Optik" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <!-- KPI 1: Frame Ready vs Stok -->
        <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Frame Siap Jual</p>
                    <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 tabular-nums">{{ number_format($readyFrameCount) }}</p>
                </div>
                <span class="w-9 h-9 rounded-lg bg-emerald-50 text-[#10b981] flex items-center justify-center flex-shrink-0">
                    <x-icon name="glasses" class="w-4 h-4" />
                </span>
            </div>
            <div class="mt-3.5">
                @php
                    $frameShare = min(100, $readyFrameCount > 0 ? round(($readyFrameCount / max(1, $totalActiveProducts)) * 100) : 0);
                @endphp
                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                    <span>Frame siap jual vs pesanan lab</span>
                    <span class="font-semibold text-[#0d9488] tabular-nums">{{ $frameShare }}% ready</span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-amber-100 overflow-hidden">
                    <div class="h-full rounded-full bg-[#10b981]" style="width: {{ $frameShare }}%"></div>
                </div>
            </div>
        </article>

        <!-- KPI 3: Ketersediaan Stok Lensa -->
        <article class="bg-white rounded-[10px] border {{ $lowStock > 0 ? 'border-amber-300' : 'border-[#e2e8f0]' }} p-4 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Ketersediaan Stok Lensa</p>
                    <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 tabular-nums">{{ number_format($totalLensStock) }}</p>
                </div>
                <span class="w-9 h-9 rounded-lg bg-amber-50 text-[#f59e0b] flex items-center justify-center flex-shrink-0">
                    <x-icon name="layers" class="w-4 h-4" />
                </span>
            </div>
            <div class="mt-3.5">
                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                    <span>{{ $lowStock > 0 ? $lowStock . ' produk stok menipis' : 'Semua stok lensa aman' }}</span>
                    <a href="{{ route('inventory.index') }}" class="font-semibold text-[#0d9488] hover:underline">Cek Stok</a>
                </div>
                <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full {{ $lowStock > 0 ? 'bg-[#f59e0b]' : 'bg-[#10b981]' }}" style="width: {{ $totalActiveProducts > 0 ? max(15, 100 - round(($lowStock / $totalActiveProducts) * 100)) : 100 }}%"></div>
                </div>
            </div>
        </article>

        <!-- KPI 4: Omzet Periode & Piutang -->
        <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Omzet Penjualan</p>
                    <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 tabular-nums truncate">Rp {{ number_format($periodRevenue, 0, ',', '.') }}</p>
                </div>
                <span class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                    <x-icon name="chart" class="w-4 h-4" />
                </span>
            </div>
            <div class="mt-3.5">
                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                    <span>Hari ini: Rp {{ number_format($todaySales, 0, ',', '.') }}</span>
                    <span class="font-semibold tabular-nums {{ $growth >= 0 ? 'text-[#10b981]' : 'text-[#ef4444]' }}">
                        {{ $growth >= 0 ? '+' : '' }}{{ $growth }}%
                    </span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-sky-500" style="width: {{ $periodRevenue > 0 ? 78 : 10 }}%"></div>
                </div>
            </div>
        </article>
    </section>

    <!-- ================= BOTTOM MAIN ANALYTICS PANEL ================= -->
    <section class="bg-white rounded-[10px] border border-[#e2e8f0] p-5">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="w-1 h-5 rounded-full bg-[#10b981]"></span>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Distribusi Penjualan &amp; Pesanan Optik</h3>
                    <p class="text-[11px] text-slate-500">Perbandingan volume lensa, frame, pesanan lab, dan retur</p>
                </div>
            </div>

            <a href="{{ route('export.sales', ['from' => $rangeFrom, 'to' => $rangeTo]) }}"
               title="Unduh Laporan CSV"
               class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg border border-[#e2e8f0] bg-white hover:bg-slate-50 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                <x-icon name="download" class="w-3.5 h-3.5" />
                <span class="hidden sm:inline">Export</span>
            </a>
        </div>

        <!-- Interactive Color-Coded Legend Grouped Left & Right -->
        <div class="flex flex-wrap items-center justify-between gap-3 py-3 text-xs">
            <div class="flex flex-wrap items-center gap-4">
                <button type="button" @click="toggleSeries('lenses')"
                        :class="seriesVisible.lenses ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3b82f6]"></span>
                    Penjualan Lensa
                </button>
                <button type="button" @click="toggleSeries('frames')"
                        :class="seriesVisible.frames ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                    Frame &amp; Aksesori
                </button>
                <button type="button" @click="toggleSeries('orders')"
                        :class="seriesVisible.orders ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#f59e0b]"></span>
                    Pesanan Optik
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <button type="button" @click="toggleSeries('returns')"
                        :class="seriesVisible.returns ? 'opacity-100' : 'opacity-40'"
                        class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition-opacity">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span>
                    Retur Produk
                </button>
                <span class="inline-flex items-center gap-1.5 text-slate-500">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-300"></span>
                    Piutang: <strong class="text-slate-700 tabular-nums">Rp {{ number_format($unpaid, 0, ',', '.') }}</strong>
                </span>
            </div>
        </div>

        <!-- Multi-Column Grouped Bar Chart -->
        <div class="relative mt-2 pt-4">
            <!-- Clean Horizontal Gridlines -->
            <div class="relative h-56 flex items-end gap-2 sm:gap-3 border-b border-[#e2e8f0] pb-1">
                <div aria-hidden="true" class="pointer-events-none absolute inset-0 flex flex-col justify-between">
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div class="border-b border-dashed border-slate-200/80 w-full"></div>
                    <div></div>
                </div>

                @foreach ($chartSeries as $idx => $item)
                    @php
                        $hLens = $maxChartVal > 0 ? max($item['lenses'] > 0 ? 8 : 2, round(($item['lenses'] / $maxChartVal) * 100)) : 2;
                        $hFrame = $maxChartVal > 0 ? max($item['frames'] > 0 ? 8 : 2, round(($item['frames'] / $maxChartVal) * 100)) : 2;
                        $hOrder = $maxChartVal > 0 ? max($item['orders'] > 0 ? 8 : 2, round(($item['orders'] / $maxChartVal) * 100)) : 2;
                        $hRet = $maxChartVal > 0 ? max($item['returns'] > 0 ? 8 : 2, round(($item['returns'] / $maxChartVal) * 100)) : 2;
                    @endphp
                    <div wire:key="chart-col-{{ $idx }}"
                         @mouseenter="hoveredIndex = {{ $idx }}"
                         @mouseleave="hoveredIndex = null"
                         class="relative z-10 flex-1 min-w-0 h-full flex flex-col items-center justify-end group">

                        <!-- Hover Tooltip -->
                        <div x-show="hoveredIndex === {{ $idx }}" x-cloak
                             class="absolute -top-2 left-1/2 -translate-x-1/2 -translate-y-full bg-slate-900 text-white rounded-md px-2.5 py-1.5 text-[10px] whitespace-nowrap shadow-lg z-20 pointer-events-none tabular-nums">
                            <div class="font-bold border-b border-white/15 pb-0.5 mb-1">{{ $item['label'] }}</div>
                            <div>Lensa: {{ $item['lenses'] }} &bull; Frame: {{ $item['frames'] }}</div>
                            <div>Order: {{ $item['orders'] }} &bull; Retur: {{ $item['returns'] }}</div>
                        </div>

                        <div class="w-full h-full flex items-end justify-center gap-1 px-0.5 sm:px-1.5">
                            <div x-show="seriesVisible.lenses"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#3b82f6] transition-all duration-200 group-hover:opacity-90"
                                 style="height: {{ $hLens }}%"></div>
                            <div x-show="seriesVisible.frames"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#10b981] transition-all duration-200 group-hover:opacity-90"
                                 style="height: {{ $hFrame }}%"></div>
                            <div x-show="seriesVisible.orders"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#f59e0b] transition-all duration-200 group-hover:opacity-90"
                                 style="height: {{ $hOrder }}%"></div>
                            <div x-show="seriesVisible.returns"
                                 class="w-2.5 sm:w-3.5 rounded-t bg-[#ef4444] transition-all duration-200 group-hover:opacity-90"
                                 style="height: {{ $hRet }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Category Labels Below Axis -->
            <div class="flex items-center gap-2 sm:gap-3 pt-2">
                @foreach ($chartSeries as $idx => $item)
                    <div class="flex-1 min-w-0 text-center text-[11px] font-medium text-slate-500 truncate tabular-nums">
                        {{ $item['label'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ================= OPERATIONAL DETAIL PANELS ================= -->
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <!-- Transaksi Terbaru (2 cols) -->
        <article class="xl:col-span-2 bg-white rounded-[10px] border border-[#e2e8f0] overflow-hidden">
            <div class="flex items-center justify-between gap-3 px-4 py-3.5 border-b border-[#e2e8f0]">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-[#3b82f6]"></span>
                    <h3 class="text-sm font-bold text-slate-900">Transaksi Terbaru</h3>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-[#0d9488] hover:underline">
                    Semua Transaksi &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-[#f4f7f9] border-b border-[#e2e8f0] text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Nota</th>
                            <th class="px-4 py-2.5 text-left">Waktu</th>
                            <th class="px-4 py-2.5 text-right">Total Bersih</th>
                            <th class="px-4 py-2.5 text-left">Status</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentSales as $s)
                            <tr wire:key="dashboard-sale-{{ $s->id }}" class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-4 py-2.5 font-semibold text-slate-900">{{ $s->code }}</td>
                                <td class="px-4 py-2.5 text-slate-500 tabular-nums whitespace-nowrap">{{ $s->sold_at->translatedFormat('d M, H:i') }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold text-slate-900 tabular-nums whitespace-nowrap">Rp {{ number_format($s->net_total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5">
                                    <div class="flex flex-wrap items-center gap-1">
                                        <span class="status-badge {{ $s->payment_status === 'lunas' ? 'success' : ($s->payment_status === 'dp' ? 'warning' : 'error') }}">
                                            {{ ucfirst(str_replace('_', ' ', $s->payment_status)) }}
                                        </span>
                                        @if ($s->return_status)
                                            <span class="status-badge {{ $s->return_status === 'Diretur' ? 'error' : 'warning' }}">{{ $s->return_status }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <a href="{{ route('sales.show', $s->id) }}" wire:navigate
                                       class="inline-flex items-center px-2.5 py-1 rounded border border-[#e2e8f0] text-[11px] font-semibold text-slate-600 hover:bg-emerald-50 hover:text-[#0d9488] hover:border-emerald-200 transition-colors">
                                        Buka
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada transaksi tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <!-- Siap Diambil & Stok Menipis (1 col) -->
        <div class="space-y-4">
            <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Pesanan Siap Diambil</h3>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-[#0d9488] text-[11px] font-bold tabular-nums">{{ $readyOrders->count() }}</span>
                </div>
                <div class="divide-y divide-slate-100 mt-2">
                    @forelse ($readyOrders as $o)
                        <a href="{{ route('optical-orders.index') }}" wire:key="ready-order-{{ $o->id }}" class="flex items-center justify-between gap-2 py-2.5 first:pt-1 last:pb-0">
                            <div class="min-w-0">
                                <span class="block text-xs font-semibold text-slate-800 truncate">{{ $o->code }}</span>
                                <span class="block text-[11px] text-slate-500 truncate">{{ $o->customer?->name ?? 'Pelanggan umum' }}</span>
                            </div>
                            <span class="status-badge success">Siap</span>
                        </a>
                    @empty
                        <p class="py-4 text-center text-xs text-slate-400">Belum ada pesanan siap diambil.</p>
                    @endforelse
                </div>
            </article>

            <article class="bg-white rounded-[10px] border border-[#e2e8f0] p-4">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Stok Lensa/Produk Menipis</h3>
                    <a href="{{ route('inventory.index') }}" class="text-[11px] font-semibold text-[#0d9488] hover:underline">Inventori &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100 mt-2">
                    @forelse ($lowStockItems as $p)
                        <div wire:key="low-stock-{{ $p->id }}" class="flex items-center justify-between gap-2 py-2.5 first:pt-1 last:pb-0">
                            <div class="min-w-0">
                                <span class="block text-xs font-semibold text-slate-800 truncate">{{ $p->name }}</span>
                                <span class="block text-[11px] text-slate-400">Min. {{ $p->min_stock }} unit</span>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[11px] font-bold tabular-nums">{{ $p->stock }} sisa</span>
                        </div>
                    @empty
                        <p class="py-4 text-center text-xs text-emerald-700 font-medium">Semua stok berada di atas batas minimum.</p>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
</div>
