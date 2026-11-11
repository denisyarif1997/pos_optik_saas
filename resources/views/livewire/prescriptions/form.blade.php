<div class="max-w-3xl mx-auto space-y-6">
    <x-page-header title="Pencatatan Resep Kacamata"
                   description="Catat parameter refraksi mata OD (Kanan) dan OS (Kiri) untuk resep kacamata pelanggan">
        <x-slot:actions>
            <a href="{{ route('customers.show', $customer->id) }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali ke Pelanggan
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl space-y-6">

        <!-- Informasi Pelanggan -->
        <div class="flex items-center gap-3 p-4 bg-canvas rounded-xl border border-line">
            <div class="w-10 h-10 rounded-full bg-emerald text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-semibold text-text-main text-sm truncate">{{ $customer->name }}</div>
                <div class="text-xs text-text-secondary mt-0.5">
                    Nomor Kontak: <span class="font-medium text-text-main">{{ $customer->phone ?? 'Tidak ada' }}</span> &middot;
                    Rekam Medis Resep Baru
                </div>
            </div>
        </div>

        <form wire:submit="save" class="space-y-6">

            <!-- Tanggal Periksa -->
            <div class="max-w-xs">
                <label class="label">Tanggal Pemeriksaan Mata</label>
                <input type="date"
                       wire:model="checked_at"
                       class="input @error('checked_at') border-error @enderror">
                @error('checked_at')
                    <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid Refraksi OD & OS -->
            <div class="grid md:grid-cols-2 gap-5">

                <!-- OD (Mata Kanan) -->
                <div class="card p-5 bg-emerald-bg/40 border border-emerald-soft rounded-2xl space-y-3.5">
                    <div class="flex items-center justify-between pb-2.5 border-b border-emerald-soft">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-emerald text-white text-xs font-bold flex items-center justify-center">R</span>
                            <span class="font-bold text-sm text-text-main">OD (Oculus Dexter / Kanan)</span>
                        </div>
                        <span class="text-[11px] font-semibold text-emerald-dark">Mata Kanan</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label text-[10px]">SPH (Sphere)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="od_sph"
                                   placeholder="-1.50 / +0.50"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">CYL (Cylinder)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="od_cyl"
                                   placeholder="-0.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">AXIS (Derajat &deg;)</label>
                            <input type="number"
                                   wire:model="od_axis"
                                   placeholder="0 - 180"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">ADD (Bifokal / Plus Baca)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="od_add"
                                   placeholder="+1.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                    </div>
                </div>

                <!-- OS (Mata Kiri) -->
                <div class="card p-5 bg-sky-50/50 border border-sky-100 rounded-2xl space-y-3.5">
                    <div class="flex items-center justify-between pb-2.5 border-b border-sky-100">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-sky-600 text-white text-xs font-bold flex items-center justify-center">L</span>
                            <span class="font-bold text-sm text-text-main">OS (Oculus Sinister / Kiri)</span>
                        </div>
                        <span class="text-[11px] font-semibold text-sky-700">Mata Kiri</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label text-[10px]">SPH (Sphere)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="os_sph"
                                   placeholder="-1.75 / +0.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">CYL (Cylinder)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="os_cyl"
                                   placeholder="-0.50"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">AXIS (Derajat &deg;)</label>
                            <input type="number"
                                   wire:model="os_axis"
                                   placeholder="0 - 180"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                        <div>
                            <label class="label text-[10px]">ADD (Bifokal / Plus Baca)</label>
                            <input type="number"
                                   step="0.25"
                                   wire:model="os_add"
                                   placeholder="+1.75"
                                   class="input bg-surface text-center font-mono font-semibold">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pupillary Distance & Catatan -->
            <div class="grid sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="label">Pupil Distance / PD (mm)</label>
                    <input type="number"
                           step="0.5"
                           wire:model="pd"
                           placeholder="Contoh: 62 / 64"
                           class="input text-center font-mono font-semibold">
                </div>

                <div class="sm:col-span-2">
                    <label class="label">Catatan Tambahan Optician</label>
                    <input type="text"
                           wire:model="notes"
                           placeholder="Contoh: Lensa anti radiasi blue light, progresif santai"
                           class="input">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-line">
                <a href="{{ route('customers.show', $customer->id) }}" wire:navigate class="btn-ghost px-5 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-7 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <x-icon name="glasses" class="w-4 h-4" />
                    <span wire:loading.remove wire:target="save">Simpan Data Resep</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
