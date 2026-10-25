<div class="max-w-xl mx-auto space-y-4">
    <h1 class="text-xl font-semibold">{{ $expenseId ? 'Ubah Pengeluaran' : 'Tambah Pengeluaran' }}</h1>
    <form wire:submit="save" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div>
            <label class="text-sm font-semibold text-slate-700">Tanggal</label>
            <input type="date" wire:model="spent_at" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            @error('spent_at') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Kategori</label>
            <input type="text" wire:model="category" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            @error('category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
            <input type="text" wire:model="description" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Jumlah</label>
            <input type="number" step="0.01" wire:model="amount" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            @error('amount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex gap-2">
            <button class="bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Simpan</button>
            <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-slate-500 font-semibold px-4 py-2.5">Batal</a>
        </div>
    </form>
</div>