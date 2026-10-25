<div class="max-w-xl mx-auto space-y-4">
    <h1 class="text-xl font-semibold">{{ $warrantyId ? 'Ubah Garansi' : 'Tambah Garansi' }}</h1>
    <form wire:submit="save" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div>
            <label class="text-sm font-semibold text-slate-700">Pelanggan</label>
            <select wire:model="customer_id" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                <option value="">-- pilih --</option>
                @foreach ($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
            @error('customer_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Nota (opsional)</label>
            <input type="number" wire:model="sale_id" placeholder="ID nota" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Berlaku sampai</label>
            <input type="date" wire:model="valid_until" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Catatan klaim</label>
            <textarea wire:model="claim_notes" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm"></textarea>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">Status</label>
            <select wire:model="status" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                <option value="active">Aktif</option>
                <option value="claimed">Diklaim</option>
                <option value="expired">Kadaluarsa</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Simpan</button>
            <a href="{{ route('warranties.index') }}" wire:navigate class="text-sm text-slate-500 font-semibold px-4 py-2.5">Batal</a>
        </div>
    </form>
</div>