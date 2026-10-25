<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-xl font-semibold tracking-tight">Penyesuaian Stok</h1>
        <p class="text-sm text-slate-500 mb-5">{{ $product->name }} • stok sekarang <b>{{ $product->stock }}</b></p>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-semibold">Jumlah (+ masuk / − keluar)</label>
                <input type="number" wire:model="qty" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 outline-none focus:ring-2 focus:ring-emerald-300" placeholder="cth: 10 atau -2">
                @error('qty') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold">Catatan</label>
                <input type="text" wire:model="notes" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5" placeholder="Stok opname / koreksi">
            </div>
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold ">Simpan & Catat →</button>
        </form>
    </div>
</div>


