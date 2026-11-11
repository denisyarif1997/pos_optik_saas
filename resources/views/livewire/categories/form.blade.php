<div class="max-w-xl mx-auto space-y-6">
    <x-page-header title="{{ $categoryId ? 'Ubah Kategori' : 'Tambah Kategori' }}"
                   description="{{ $categoryId ? 'Perbarui nama atau status aktif kategori produk' : 'Tambah kelompok kategori produk baru ke katalog toko Anda' }}">
        <x-slot:actions>
            <a href="{{ route('categories.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-line">
            <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                <x-icon name="tag" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="font-semibold text-base text-text-main leading-tight">{{ $categoryId ? 'Form Ubah Kategori' : 'Form Kategori Baru' }}</h2>
                <p class="text-xs text-text-secondary mt-0.5">Kategori mempermudah pencarian saat kasir dan inventori</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="label">Nama Kategori <span class="text-error">*</span></label>
                <input type="text"
                       wire:model="name"
                       class="input @error('name') border-error @enderror"
                       placeholder="Contoh: Frame Pria, Lensa Bluechromic, Cairan Pembersih">
                @error('name')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-3 bg-canvas rounded-xl border border-line flex items-center justify-between">
                <div>
                    <label for="cat_active" class="text-xs font-semibold text-text-main cursor-pointer">Status Kategori Aktif</label>
                    <p class="text-[11px] text-text-secondary">Kategori aktif dapat dipilih saat menambah produk baru</p>
                </div>
                <input type="checkbox"
                       wire:model="is_active"
                       id="cat_active"
                       class="w-4 h-4 rounded text-emerald accent-emerald cursor-pointer">
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-line">
                <a href="{{ route('categories.index') }}" wire:navigate class="btn-ghost px-4 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-6 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Kategori</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
