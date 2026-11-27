<div class="space-y-5">
    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3 sm:items-end">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Laporan Kas Masuk</h1>
            <p class="text-xs text-slate-500">Histori pemasukan kas sepanjang waktu</p>
        </div>
        <div class="sm:ml-auto flex gap-3">
            <label class="text-xs">Dari
                <input type="date" wire:model.live="from" class="mt-1 block rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2">
            </label>
            <label class="text-xs">Sampai
                <input type="date" wire:model.live="to" class="mt-1 block rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2">
            </label>
            <label class="text-xs">Akun Kas
                <select wire:model="cashAccountId" class="mt-1 block rounded-lg bg-slate-50 border border-emerald-100 px-3 py-2">
                    <option value="">Semua</option>
                    @foreach ($cashAccountOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-semibold">Total Kas Masuk</h2>
            <span class="text-lg font-bold text-emerald-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Per Akun Kas -->
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Per Akun Kas</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Akun Kas</th>
                        <th class="py-3 px-4 text-right">Jumlah Transaksi</th>
                        <th class="py-3 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($byAccount as $row)
                        <tr wire:key="cash-in-account-{{ $row->account_name }}">
                            <td class="py-3 px-4">{{ $row->account_name }}</td>
                            <td class="py-3 px-4 text-right">{{ $row->count }}</td>
                            <td class="py-3 px-4 text-right">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-sm text-slate-400">Belum ada data kas masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Detail Transaksi Kas Masuk</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Akun Kas</th>
                        <th class="py-3 px-4">Dicatat Oleh</th>
                        <th class="py-3 px-4 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($cashIns as $cashIn)
                        <tr wire:key="cash-in-{{ $cashIn->id }}">
                            <td class="py-3 px-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($cashIn->received_at)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4">{{ $cashIn->description ?? '-' }}</td>
                            <td class="py-3 px-4">{{ ucfirst($cashIn->category ?? '-') }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $cashIn->cashAccount?->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $cashIn->creator?->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-right font-medium">Rp {{ number_format($cashIn->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-slate-400">Belum ada data kas masuk pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-custom-pagination :paginator="$cashIns" />
    </div>
</div>