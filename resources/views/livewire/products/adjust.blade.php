<div class="max-w-xl mx-auto space-y-6">
    <x-page-header title="Penyesuaian Stok Barang"
                   description="Koreksi stok masuk atau keluar manual di luar transaksi reguler">
        <x-slot:actions>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5">
        <!-- Info Produk -->
        <div class="flex items-center gap-3 p-4 bg-canvas rounded-xl border border-line">
            <div class="w-10 h-10 rounded-xl bg-emerald text-white flex items-center justify-center font-bold shadow-xs flex-shrink-0">
                <x-icon name="box" class="w-5 h-5" />
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-semibold text-text-main text-sm truncate">{{ $product->name }}</div>
                <div class="text-xs text-text-secondary mt-0.5">
                    Tipe: <span class="capitalize font-medium text-text-main">{{ $product->type }}</span> &middot;
                    Stok Sistem Saat Ini: <span class="font-bold text-emerald-dark">{{ $product->stock }} unit</span>
                </div>
            </div>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="label">Jumlah Penyesuaian (+ Masuk / - Keluar) <span class="text-error">*</span></label>
                <input type="number"
                       wire:model="qty"
                       class="input @error('qty') border-error @enderror"
                       placeholder="Contoh: 10 untuk tambah, -3 untuk pengurangan">
                <p class="text-[11px] text-text-secondary mt-1">Gunakan angka positif (+) untuk stok masuk, atau tanda minus (-) untuk stok keluar/rusak.</p>
                @error('qty')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Alasan / Catatan Penyesuaian</label>
                <input type="text"
                       wire:model="notes"
                       class="input"
                       placeholder="Contoh: Barang cacat dari pabrik / Hasil opname berkala">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-4 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-6 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan &amp; Perbarui Stok</span>
                    <span wire:loading wire:target="save">Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>
