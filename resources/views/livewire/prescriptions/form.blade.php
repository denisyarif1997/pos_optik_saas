<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-xl font-semibold tracking-tight">Resep Baru</h1>
        <p class="text-sm text-slate-500 mb-5">Pelanggan: <b>{{ $customer->name }}</b> • hanya dicatat, tanpa diagnosis otomatis</p>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-semibold">Tanggal periksa</label>
                <input type="date" wire:model="checked_at" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="rounded-lg bg-emerald-50/60 border border-emerald-100 p-4 space-y-3">
                    <div class="font-semibold text-sm">OD (Kanan)</div>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="text-xs">SPH<input type="number" step="0.25" wire:model="od_sph" class="mt-1 w-full rounded-xl bg-white border border-emerald-100 px-3 py-2"></label>
                        <label class="text-xs">CYL<input type="number" step="0.25" wire:model="od_cyl" class="mt-1 w-full rounded-xl bg-white border border-emerald-100 px-3 py-2"></label>
                        <label class="text-xs">AXIS<input type="number" wire:model="od_axis" class="mt-1 w-full rounded-xl bg-white border border-emerald-100 px-3 py-2"></label>
                        <label class="text-xs">ADD<input type="number" step="0.25" wire:model="od_add" class="mt-1 w-full rounded-xl bg-white border border-emerald-100 px-3 py-2"></label>
                    </div>
                </div>
                <div class="rounded-lg bg-teal-50/60 border border-teal-100 p-4 space-y-3">
                    <div class="font-semibold text-sm">OS (Kiri)</div>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="text-xs">SPH<input type="number" step="0.25" wire:model="os_sph" class="mt-1 w-full rounded-xl bg-white border border-teal-100 px-3 py-2"></label>
                        <label class="text-xs">CYL<input type="number" step="0.25" wire:model="os_cyl" class="mt-1 w-full rounded-xl bg-white border border-teal-100 px-3 py-2"></label>
                        <label class="text-xs">AXIS<input type="number" wire:model="os_axis" class="mt-1 w-full rounded-xl bg-white border border-teal-100 px-3 py-2"></label>
                        <label class="text-xs">ADD<input type="number" step="0.25" wire:model="os_add" class="mt-1 w-full rounded-xl bg-white border border-teal-100 px-3 py-2"></label>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">PD</label>
                    <input type="number" step="0.5" wire:model="pd" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
                <div>
                    <label class="text-sm font-semibold">Catatan</label>
                    <input type="text" wire:model="notes" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5">
                </div>
            </div>
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold ">Simpan Resep →</button>
        </form>
    </div>
</div>


