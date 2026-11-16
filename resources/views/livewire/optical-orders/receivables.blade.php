<div class="space-y-4">
    <div class="flex items-center justify-between gap-3">
        <h1 class="text-xl font-semibold">Piutang Pesanan Kacamata</h1>
        <input type="text" wire:model.live="search" placeholder="Cari kode/pelanggan..." class="rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2 text-sm w-64">
    </div>
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left table-head">
                    <th class="py-2 px-3">Kode</th>
                    <th class="px-3">Pelanggan</th>
                    <th class="px-3">Janji</th>
                    <th class="px-3">Status</th>
                    <th class="px-3 text-right">Total</th>
                    <th class="px-3 text-right">Terbayar</th>
                    <th class="px-3 text-right">Sisa</th>
                    <th class="px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $o)
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="py-2 px-3 font-bold">{{ $o->code ?? $o->order_number }}</td>
                        <td class="px-3">{{ $o->customer?->name ?? '-' }}</td>
                        <td class="px-3">{{ $o->promised_date?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-3"><span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">{{ $o->status }}</span></td>
                        <td class="px-3 text-right">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td class="px-3 text-right text-emerald-700">Rp {{ number_format($o->dp_total, 0, ',', '.') }}</td>
                        <td class="px-3 text-right text-red-600 font-bold">Rp {{ number_format($o->receivable, 0, ',', '.') }}</td>
                        <td class="px-3 text-right">
                            <a href="{{ route('optical-orders.show', $o->id) }}" wire:navigate class="text-xs font-bold text-emerald-700">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-6 text-center text-sm text-slate-400">Tidak ada piutang. Semua pesanan lunas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="text-xs text-slate-500">Total piutang: Rp {{ number_format($orders->sum('receivable'), 0, ',', '.') }}</div>
</div>