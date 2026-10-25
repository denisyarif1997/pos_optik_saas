<div class="space-y-5">
    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3 sm:items-end">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Laporan Toko</h1>
            <p class="text-xs text-slate-500">Hanya data tokomu • laba kotor bukan laba bersih</p>
        </div>
        <div class="sm:ml-auto flex gap-3">
            <label class="text-xs">Dari<input type="date" wire:model.live="from" class="mt-1 block rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2"></label>
            <label class="text-xs">Sampai<input type="date" wire:model.live="to" class="mt-1 block rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2"></label>
            <a href="{{ route('export.sales', ['from' => $from, 'to' => $to]) }}" class="self-end text-xs font-bold px-4 py-2.5 rounded-lg bg-emerald-500 text-white">⬇ CSV</a>
        </div>
    </div>
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5"><div class="text-xs text-slate-400">Omzet</div><div class="text-xl font-semibold text-emerald-700">Rp {{ number_format($revenue, 0, ',', '.') }}</div><div class="text-xs text-slate-400">{{ $count }} transaksi</div></div>
        <div class="bg-white rounded-xl border border-slate-200 p-5"><div class="text-xs text-slate-400">Kas masuk</div><div class="text-xl font-semibold">Rp {{ number_format($collected, 0, ',', '.') }}</div></div>
        <div class="bg-white rounded-xl border border-slate-200 p-5"><div class="text-xs text-slate-400">Piutang</div><div class="text-xl font-semibold text-amber-600">Rp {{ number_format($receivable, 0, ',', '.') }}</div></div>
        <div class="bg-white rounded-xl border border-slate-200 p-5"><div class="text-xs text-slate-400">Pengeluaran</div><div class="text-xl font-semibold text-red-600">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</div></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Laba / Rugi (Periode)</h2>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="flex justify-between"><span>Omzet</span><span class="font-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>HPP (COGS)</span><span class="font-bold text-red-600">Rp {{ number_format($cogs, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Laba Kotor</span><span class="font-bold text-emerald-700">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Pengeluaran</span><span class="font-bold text-red-600">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between col-span-2 border-t pt-2"><span class="font-semibold">Laba Bersih</span><span class="font-bold text-lg {{ $netProfit >= 0 ? 'text-emerald-700' : 'text-red-600' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Produk Terlaris</h2>
        @forelse ($topProducts as $t)
            <div class="flex justify-between text-sm py-2 border-b border-slate-50 last:border-0"><span class="font-bold">{{ $t->product?->name ?? '-' }} <span class="text-slate-400 font-normal">× {{ $t->qty }}</span></span><span>Rp {{ number_format($t->omzet, 0, ',', '.') }}</span></div>
        @empty
            <p class="text-sm text-slate-400 text-center py-4">Belum ada penjualan pada periode ini.</p>
        @endforelse
    </div>
</div>


