<div class="max-w-xl mx-auto space-y-6">
    <x-page-header title="{{ $expenseId ? 'Ubah Biaya Pengeluaran' : 'Catat Pengeluaran Baru' }}"
                   description="{{ $expenseId ? 'Perbarui data catatan pengeluaran operasional toko' : 'Catat biaya operasional toko seperti listrik, sewa, gaji, atau perlengkapan' }}">
        <x-slot:actions>
            <a href="{{ route('expenses.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-line">
            <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                <x-icon name="wallet" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="font-semibold text-base text-text-main leading-tight">Rincian Pengeluaran</h2>
                <p class="text-xs text-text-secondary mt-0.5">Pengeluaran akan tercatat dalam laporan laba &amp; rugi toko</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="label">Tanggal Pengeluaran <span class="text-error">*</span></label>
                <input type="date"
                       wire:model="spent_at"
                       class="input @error('spent_at') border-error @enderror">
                @error('spent_at')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Kategori Pengeluaran <span class="text-error">*</span></label>
                <input type="text"
                       wire:model="category"
                       placeholder="Contoh: Listrik &amp; Air, Gaji, Perlengkapan, Sewa Toko"
                       class="input @error('category') border-error @enderror">
                @error('category')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Deskripsi / Keterangan</label>
                <input type="text"
                       wire:model="description"
                       placeholder="Contoh: Pembayaran tagihan PLN bulan berjalan"
                       class="input @error('description') border-error @enderror">
                @error('description')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Nominal Pengeluaran (Rp) <span class="text-error">*</span></label>
                <input type="number"
                       step="0.01"
                       wire:model="amount"
                       placeholder="0"
                       class="input @error('amount') border-error @enderror">
                @error('amount')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                <a href="{{ route('expenses.index') }}" wire:navigate class="btn-ghost px-4 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-6 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Pengeluaran</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>