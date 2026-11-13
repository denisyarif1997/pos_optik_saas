<div>
    <x-page-header title="Pesanan Kacamata" description="Lacak pengerjaan sampai diambil">
        <x-slot:actions>
            <a href="{{ route('optical-orders.receivables') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Piutang</a>
            <a href="{{ route('optical-orders.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Pesanan Baru</a>
        </x-slot:actions>
    </x-page-header>

    <div class="flex items-center gap-3 mb-4">
        <select wire:model.live="status" class="input w-64">
            <option value="">Semua status</option>
            <option value="baru">Baru</option>
            <option value="diproses">Diproses</option>
            <option value="menunggu_lensa">Menunggu lensa</option>
            <option value="siap_diambil">Siap diambil</option>
            <option value="diambil">Diambil</option>
            <option value="selesai">Selesai</option>
            <option value="dibatalkan">Dibatalkan</option>
        </select>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left w-32">Kode Pesanan</th>
                        <th class="py-3 px-4 text-left">Pelanggan</th>
                        <th class="py-3 px-4 text-right w-32">Total</th>
                        <th class="py-3 px-4 text-right w-32">DP</th>
                        <th class="py-3 px-4 text-left w-20">Status</th>
                        <th class="py-3 px-4 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($orders as $o)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-text-main">{{ $o->code }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $o->customer?->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-[11px] font-semibold text-text-main text-right">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary text-right">Rp {{ number_format($o->dp_total, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $orderBadge = match ($o->status) {
                                        'siap_diambil', 'selesai' => 'success',
                                        'dibatalkan' => 'error',
                                        default => 'info',
                                    };
                                @endphp
                                <x-status-badge :status="ucfirst(str_replace('_', ' ', $o->status))" :class="$orderBadge" />
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('optical-orders.show', $o->id) }}" wire:navigate
                                    class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>