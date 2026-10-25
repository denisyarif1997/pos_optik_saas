<div class="space-y-5">
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Riwayat Transaksi</h1>
            <p class="text-xs text-slate-500">Semua penjualan tokomu</p>
        </div>
        <a href="{{ route('pos.cashier') }}" wire:navigate class="ml-auto bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg ">+ Transaksi Baru</a>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <input type="text" wire:model.live="search" placeholder="Cari kode nota..." class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100"><th class="py-2.5 pr-3">Kode</th><th class="pr-3">Waktu</th><th class="text-right pr-3">Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($sales as $s)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-2.5 pr-3 font-bold">{{ $s->code }}</td>
                        <td class="pr-3 text-slate-500">{{ $s->sold_at->format('d M H:i') }}</td>
                        <td class="text-right pr-3 font-bold">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                        <td><span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $s->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $s->payment_status }}</span></td>
                        <td class="text-right"><a href="{{ route('sales.show', $s->id) }}" wire:navigate class="text-emerald-700 font-bold text-xs">Nota →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-6 text-center text-slate-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $sales->links() }}
</div>


