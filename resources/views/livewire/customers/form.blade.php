<div class="max-w-2xl mx-auto space-y-6">
    <x-page-header title="{{ $customerId ? 'Ubah Data Pelanggan' : 'Tambah Pelanggan Baru' }}"
                   description="{{ $customerId ? 'Perbarui informasi identitas pelanggan dan kontak' : 'Daftarkan pelanggan baru untuk pencatatan resep kacamata dan transaksi penjualan' }}">
        <x-slot:actions>
            <a href="{{ route('customers.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-line">
            <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                <x-icon name="users" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="font-semibold text-base text-text-main leading-tight">Formulir Identitas Pelanggan</h2>
                <p class="text-xs text-text-secondary mt-0.5">Informasi kontak pelanggan untuk riwayat resep kacamata</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="label">Nama Lengkap <span class="text-error">*</span></label>
                <input type="text"
                       wire:model="name"
                       class="input @error('name') border-error @enderror"
                       placeholder="Contoh: Budi Santoso">
                @error('name')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Nomor WhatsApp / HP</label>
                    <input type="text"
                           wire:model="phone"
                           class="input @error('phone') border-error @enderror"
                           placeholder="08123456789">
                    @error('phone')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label">Tanggal Lahir</label>
                    <input type="date"
                           wire:model="birth_date"
                           class="input @error('birth_date') border-error @enderror">
                    @error('birth_date')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="label">Alamat Tinggal</label>
                <textarea wire:model="address"
                          rows="3"
                          class="input @error('address') border-error @enderror"
                          placeholder="Jl. Merdeka No. 12, Kelurahan, Kecamatan, Kota"></textarea>
                @error('address')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                <a href="{{ route('customers.index') }}" wire:navigate class="btn-ghost px-5 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-7 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <x-icon name="check" class="w-4 h-4" />
                    <span wire:loading.remove wire:target="save">Simpan Pelanggan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
