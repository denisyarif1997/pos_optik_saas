<div class="grid lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h1 class="font-semibold mb-1">Catat Pengeluaran</h1>
        <p class="text-xs text-slate-500 mb-4">Bulan ini: <b>Rp {{ number_format($monthTotal, 0, ',', '.') }}</b></p>
        <form wire:submit="save" class="space-y-3">
            <input type="date" wire:model="spent_at" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <select wire:model="category" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                <option value="operasional">Operasional</option>
                <option value="gaji">Gaji</option>
                <option value="sewa">Sewa</option>
                <option value="listrik">Listrik & Air</option>
                <option value="lainnya">Lainnya</option>
            </select>
            <input type="text" wire:model="description" placeholder="Keterangan" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            @error('description') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            <input type="number" wire:model="amount" placeholder="Nominal" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">+ Simpan</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Terakhir Dicatat</h2>
        @forelse ($expenses as $e)
            <div class="flex items-center gap-3 text-sm py-2 border-b border-slate-50 last:border-0">
                <span class="flex-1"><b>{{ $e->description }}</b> <span class="text-slate-400">• {{ $e->category }} • {{ $e->spent_at->format('d M') }}</span></span>
                <b class="text-red-600">Rp {{ number_format($e->amount, 0, ',', '.') }}</b>
                <a href="{{ route('expenses.edit', $e->id) }}" wire:navigate class="text-xs font-bold text-slate-400">Ubah</a>
<button wire:click="delete({{ $e->id }})" wire:confirm="Hapus?" class="text-xs text-slate-400">✕</button>
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-4">Belum ada pengeluaran.</p>
        @endforelse
    </div>
</div>


