<div>
    <x-page-header title="{{ $productId ? 'Ubah Produk' : 'Tambah Produk' }}"
        description="{{ $productId ? 'Perbarui data produk dan stok' : 'Tambah produk baru ke katalog toko Anda' }}">
        <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Kembali</a>
    </x-page-header>

    <form wire:submit="save" class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <label class="label block mb-1.5">Nama Produk <span class="text-error">*</span></label>
            <input type="text" wire:model="name" class="input"
                placeholder="Contoh: Frame Kacamata Titanium">
            @error('name') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label block mb-1.5">Tipe <span class="text-error">*</span></label>
            <select wire:model="type" class="input">
                <option value="frame">Frame</option>
                <option value="lensa">Lensa</option>
                <option value="softlens">Softlens</option>
                <option value="cairan">Cairan</option>
                <option value="aksesori">Aksesori</option>
            </select>
            @error('type') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label block mb-1.5">Kategori</label>
            <select wire:model="category_id" class="input">
                <option value="">— Tanpa kategori —</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label block mb-1.5">SKU</label>
            <input type="text" wire:model="sku" class="input"
                placeholder="SKU produk (opsional)">
            @error('sku') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label block mb-1.5">Barcode</label>
            <input type="text" wire:model="barcode" class="input"
                placeholder="Barcode (opsional)">
            @error('barcode') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label block mb-1.5">Harga Jual <span class="text-error">*</span></label>
            <input type="number" wire:model="sell_price" class="input"
                placeholder="0">
            @error('sell_price') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label block mb-1.5">Harga Pokok <span class="text-error">*</span></label>
            <input type="number" wire:model="cost_price" class="input"
                placeholder="0">
            @error('cost_price') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label block mb-1.5">Stok Awal <span class="text-error">*</span></label>
            <input type="number" wire:model="stock" @if($productId) disabled @endif class="input"
                placeholder="0">
            @error('stock') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
            @if($productId)
                <p class="text-[10px] text-text-secondary mt-1">Stok hanya dapat diubah melalui menu Sesuaikan Stok.</p>
            @endif
        </div>

        <div>
            <label class="label block mb-1.5">Stok Minimum <span class="text-error">*</span></label>
            <input type="number" wire:model="min_stock" class="input"
                placeholder="0">
            @error('min_stock') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2 flex justify-end gap-2 pt-4 border-t border-line">
            <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Batal</a>
            <button type="submit" class="btn-primary px-6 py-2 text-sm font-semibold">Simpan Produk</button>
        </div>
    </form>
</div>


