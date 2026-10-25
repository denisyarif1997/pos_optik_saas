<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Hitung Opname {{ $opname->code }}</h1>
        <div class="flex gap-2">
            <button wire:click="cancel" wire:confirm="Batalkan opname ini?" class="text-sm text-slate-500 font-semibold px-4 py-2.5">Batal</button>
            <button wire:click="finalize" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Finalisasi & Sesuaikan Stok</button>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left table-head">
                    <th class="py-2 px-3">Produk</th>
                    <th class="px-3 text-right">Stok Sistem</th>
                    <th class="px-3 text-right">Hitung Fisik</th>
                    <th class="px-3 text-right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($opname->items as $it)
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="py-2 px-3">{{ $it->product?->name ?? '-' }}</td>
                        <td class="px-3 text-right">{{ $it->system_qty }}</td>
                        <td class="px-3 text-right">
                            <input type="number" wire:model="counts.{{ $it->id }}" class="w-24 text-right rounded-lg bg-slate-50 border border-emerald-100 px-2 py-1.5 text-sm">
                        </td>
                        <td class="px-3 text-right {{ (($counts[$it->id] ?? $it->system_qty) - $it->system_qty) !== 0 ? 'text-red-600 font-bold' : 'text-slate-400' }}">
                            {{ ($counts[$it->id] ?? $it->system_qty) - $it->system_qty }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>