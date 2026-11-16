<div class="grid lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h1 class="font-semibold mb-1">Tambah Supplier</h1>
        <p class="text-xs text-slate-500 mb-4">Distributor frame / lab lensa.</p>
        <form wire:submit="save" class="space-y-3">
            <input type="text" wire:model="name" placeholder="Nama supplier" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
            @error('name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            <input type="text" wire:model="phone" placeholder="HP (opsional)" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">+ Simpan</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Daftar Supplier</h2>
        <div class="grid sm:grid-cols-2 gap-2">
            @forelse ($suppliers as $s)
                <div class="flex items-center gap-2 rounded-lg border border-emerald-100 px-3 py-2.5 text-sm">
                    <span class="flex-1 min-w-0"><b class="block truncate">{{ $s->name }}</b><span class="text-xs text-slate-400">{{ $s->phone ?? '-' }}</span></span>
                    <button wire:click="toggle({{ $s->id }})" class="text-xs font-bold text-emerald-700">{{ $s->is_active ? 'Aktif' : 'Off' }}</button>
<a href="{{ route('suppliers.edit', $s->id) }}" wire:navigate class="text-xs font-bold text-slate-500">Ubah</a>
                </div>
            @empty
                <p class="text-sm text-slate-400">Belum ada supplier.</p>
            @endforelse
        </div>
    </div>
</div>


