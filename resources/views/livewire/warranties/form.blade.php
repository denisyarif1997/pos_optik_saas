<div class="max-w-xl mx-auto space-y-6">
    <x-page-header title="{{ $warrantyId ? 'Ubah Data Garansi' : 'Tambah Kartu Garansi' }}"
                   description="{{ $warrantyId ? 'Perbarui masa berlaku atau riwayat catatan klaim garansi' : 'Daftarkan garansi kacamata baru untuk pelanggan' }}">
        <x-slot:actions>
            <a href="{{ route('warranties.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-line">
            <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                <x-icon name="shield" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="font-semibold text-base text-text-main leading-tight">Detail Garansi Kacamata</h2>
                <p class="text-xs text-text-secondary mt-0.5">Masa garansi frame, coating lensa, atau servis penyetelan</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="label">Nama Pelanggan <span class="text-error">*</span></label>
                <select wire:model="customer_id" class="input @error('customer_id') border-error @enderror">
                    <option value="">— Pilih Pelanggan —</option>
                    @foreach ($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? 'Tanpa HP' }})</option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">ID Nota Penjualan (Opsional)</label>
                    <input type="number"
                           wire:model="sale_id"
                           placeholder="Nomor ID nota"
                           class="input @error('sale_id') border-error @enderror">
                    @error('sale_id')
                        <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Masa Berlaku Sampai <span class="text-error">*</span></label>
                    <input type="date"
                           wire:model="valid_until"
                           class="input @error('valid_until') border-error @enderror">
                    @error('valid_until')
                        <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="label">Status Garansi <span class="text-error">*</span></label>
                <select wire:model="status" class="input @error('status') border-error @enderror">
                    <option value="active">Aktif (Masih Berlaku)</option>
                    <option value="claimed">Diklaim (Sudah Dilakukan Penggantian/Servis)</option>
                    <option value="expired">Kadaluarsa (Masa Garansi Habis)</option>
                </select>
                @error('status')
                    <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Catatan Garansi / Keluhan Klaim</label>
                <textarea wire:model="claim_notes"
                          rows="3"
                          placeholder="Contoh: Garansi coating terkelupas selama 6 bulan / Penyetelan nose pad gratis"
                          class="input @error('claim_notes') border-error @enderror"></textarea>
                @error('claim_notes')
                    <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                <a href="{{ route('warranties.index') }}" wire:navigate class="btn-ghost px-4 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-6 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Garansi</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>