<div class="max-w-xl mx-auto space-y-6">
    <x-page-header title="{{ $supplierId ? 'Ubah Data Supplier' : 'Tambah Supplier Baru' }}"
                   description="{{ $supplierId ? 'Perbarui informasi kontak pemasok lensa/frame' : 'Daftarkan supplier baru untuk transaksi pembelian dan pengadaan barang' }}">
        <x-slot:actions>
            <a href="{{ route('suppliers.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-line">
            <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                <x-icon name="truck" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="font-semibold text-base text-text-main leading-tight">Informasi Supplier / Distributor</h2>
                <p class="text-xs text-text-secondary mt-0.5">Data kontak supplier untuk kebutuhan pembelian barang</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="label">Nama Supplier / PT <span class="text-error">*</span></label>
                <input type="text"
                       wire:model="name"
                       placeholder="Contoh: PT. Domas Lensa Indonesia / Essilor Distributor"
                       class="input @error('name') border-error @enderror">
                @error('name')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Nomor Telepon / Kontak Sales</label>
                <input type="text"
                       wire:model="phone"
                       placeholder="08123456789 / 021-5551234"
                       class="input @error('phone') border-error @enderror">
                @error('phone')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-3 bg-canvas rounded-xl border border-line flex items-center justify-between">
                <div>
                    <label for="supp_active" class="text-xs font-semibold text-text-main cursor-pointer">Status Supplier Aktif</label>
                    <p class="text-[11px] text-text-secondary">Supplier aktif dapat dipilih pada menu pembelian barang</p>
                </div>
                <input type="checkbox"
                       wire:model="is_active"
                       id="supp_active"
                       class="w-4 h-4 rounded text-emerald accent-emerald cursor-pointer">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                <a href="{{ route('suppliers.index') }}" wire:navigate class="btn-ghost px-4 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-6 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Supplier</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>