<div class="max-w-xl mx-auto space-y-4">
    <h1 class="text-xl font-semibold">{{ $supplierId ? 'Ubah Supplier' : 'Tambah Supplier' }}</h1>
    <form wire:submit="save" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div>
            <label class="text-sm font-semibold text-slate-700">Nama</label>
            <input type="text" wire:model="name" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Telepon</label>
            <input type="text" wire:model="phone" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" wire:model="is_active" id="active">
            <label for="active" class="text-sm text-slate-700">Aktif</label>
        </div>
        <div class="flex gap-2">
            <button class="bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Simpan</button>
            <a href="{{ route('suppliers.index') }}" wire:navigate class="text-sm text-slate-500 font-semibold px-4 py-2.5">Batal</a>
        </div>
    </form>
</div>