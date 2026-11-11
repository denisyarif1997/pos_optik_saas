<div class="max-w-4xl mx-auto space-y-6">
    <x-page-header title="{{ $productId ? 'Ubah Data Produk' : 'Tambah Produk Baru' }}"
                   description="{{ $productId ? 'Perbarui data katalog produk, harga jual, dan batas stok toko Anda' : 'Daftarkan frame, lensa, cairan, atau aksesori baru ke katalog toko Anda' }}">
        <x-slot:actions>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl">
        <form wire:submit="save" class="space-y-6">

            <!-- Bagian 1: Informasi Dasar -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 mb-4 border-b border-line">
                    <div class="w-8 h-8 rounded-lg bg-emerald-bg text-emerald flex items-center justify-center font-bold text-xs">1</div>
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Informasi Dasar Produk</h2>
                        <p class="text-[11px] text-text-secondary">Nama dagang, kelompok tipe, dan kategori</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="label">Nama Produk <span class="text-error">*</span></label>
                        <input type="text"
                               wire:model="name"
                               class="input @error('name') border-error @enderror"
                               placeholder="Contoh: Frame Titanium Aviator Black 52mm">
                        @error('name')
                            <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Tipe Produk <span class="text-error">*</span></label>
                        <select wire:model="type" class="input @error('type') border-error @enderror">
                            <option value="frame">Frame (Bingkai Kacamata)</option>
                            <option value="lensa">Lensa Kacamata</option>
                            <option value="softlens">Lensa Kontak / Softlens</option>
                            <option value="cairan">Cairan Pembersih &amp; Solution</option>
                            <option value="aksesori">Aksesori &amp; Kotak Kacamata</option>
                        </select>
                        @error('type')
                            <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Kategori Produk</label>
                        <select wire:model="category_id" class="input">
                            <option value="">— Pilih Kategori (Opsional) —</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Identifikasi & Kode -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 mb-4 border-b border-line">
                    <div class="w-8 h-8 rounded-lg bg-emerald-bg text-emerald flex items-center justify-center font-bold text-xs">2</div>
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Kode &amp; Barcode (Opsional)</h2>
                        <p class="text-[11px] text-text-secondary">Digunakan saat scan barcode di kasir POS</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Kode SKU / Kode Barang</label>
                        <input type="text"
                               wire:model="sku"
                               class="input @error('sku') border-error @enderror"
                               placeholder="Contoh: FRM-AV-01">
                        @error('sku')
                            <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Nomor Barcode / EAN</label>
                        <input type="text"
                               wire:model="barcode"
                               class="input @error('barcode') border-error @enderror"
                               placeholder="Scan atau ketik barcode...">
                        @error('barcode')
                            <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Harga & Stok -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 mb-4 border-b border-line">
                    <div class="w-8 h-8 rounded-lg bg-emerald-bg text-emerald flex items-center justify-center font-bold text-xs">3</div>
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Harga &amp; Batas Stok</h2>
                        <p class="text-[11px] text-text-secondary">Penentuan harga jual serta peringatan persediaan minimum</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Harga Jual (Rp) <span class="text-error">*</span></label>
                        <input type="number"
                               wire:model="sell_price"
                               class="input @error('sell_price') border-error @enderror"
                               placeholder="Contoh: 350000">
                        @error('sell_price')
                            <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Harga Pokok / Modal (Rp) <span class="text-error">*</span></label>
                        <input type="number"
                               wire:model="cost_price"
                               class="input @error('cost_price') border-error @enderror"
                               placeholder="Contoh: 180000">
                        @error('cost_price')
                            <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Stok Awal Fisik <span class="text-error">*</span></label>
                        <input type="number"
                               wire:model="stock"
                               @if($productId) disabled @endif
                               class="input @error('stock') border-error @enderror @if($productId) bg-canvas text-text-muted cursor-not-allowed @endif"
                               placeholder="0">
                        @error('stock')
                            <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                        @enderror
                        @if($productId)
                            <p class="text-[10px] text-text-secondary mt-1">Stok hanya dapat disesuaikan lewat menu <em>Sesuaikan Stok</em> atau <em>Stock Opname</em>.</p>
                        @endif
                    </div>

                    <div>
                        <label class="label">Batas Minimum Peringatan Stok <span class="text-error">*</span></label>
                        <input type="number"
                               wire:model="min_stock"
                               class="input @error('min_stock') border-error @enderror"
                               placeholder="Contoh: 5">
                        @error('min_stock')
                            <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-line">
                <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-5 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-7 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <x-icon name="check" class="w-4 h-4" />
                    <span wire:loading.remove wire:target="save">Simpan Produk</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
