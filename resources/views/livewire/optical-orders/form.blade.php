<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-xl font-semibold tracking-tight mb-5">{{ $orderId ? 'Ubah Pesanan' : 'Pesanan Kacamata Baru' }}</h1>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-semibold">Pelanggan</label>
                <select wire:model.live="customer_id" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                    <option value="">— Pilih —</option>
                    @foreach ($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
                @error('customer_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Lab / supplier lensa</label>
                    <input type="text" wire:model="lab_name" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
                <div>
                    <label class="text-sm font-semibold">Estimasi selesai</label>
                    <input type="date" wire:model="estimated_done_at" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
                <div>
                    <label class="text-sm font-semibold">Total harga</label>
                    <input type="number" wire:model="total" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
                <div>
                    <label class="text-sm font-semibold">DP</label>
                    <input type="number" wire:model="dp_total" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
            </div>
            @if (!$orderId)
                <div>
                    <label class="text-sm font-semibold">Frame dari stok (opsional)</label>
                    <div class="mt-2 grid sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                        @foreach ($frames as $f)
                            <label class="flex items-center gap-2 text-sm rounded-lg border border-emerald-100 px-3 py-2"><input type="checkbox" wire:model="frameIds" value="{{ $f->id }}" class="accent-emerald-600"><span class="truncate">{{ $f->name }}</span></label>
                        @endforeach
                    </div>
                </div>
            @endif
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold ">Simpan Pesanan →</button>
        </form>
    </div>
</div>


