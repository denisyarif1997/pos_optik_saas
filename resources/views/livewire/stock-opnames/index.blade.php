<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Stock Opname</h1>
        <button wire:click="create" class="bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">+ Mulai Opname Baru</button>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left table-head">
                    <th class="py-2 px-3">Kode</th>
                    <th class="px-3">Tanggal</th>
                    <th class="px-3">Status</th>
                    <th class="px-3">Oleh</th>
                    <th class="px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($opnames as $o)
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="py-2 px-3 font-bold">{{ $o->code }}</td>
                        <td class="px-3">{{ $o->conducted_at->format('d/m/Y') }}</td>
                        <td class="px-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $o->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($o->status === 'cancelled' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700') }}">{{ $o->status }}</span>
                        </td>
                        <td class="px-3">{{ $o->conductor?->name ?? '-' }}</td>
                        <td class="px-3 text-right">
                            @if ($o->status === 'draft')
                                <a href="{{ route('stock-opnames.count', $o->id) }}" wire:navigate class="text-xs font-bold text-emerald-700">Hitung</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-6 text-center text-sm text-slate-400">Belum ada opname.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>