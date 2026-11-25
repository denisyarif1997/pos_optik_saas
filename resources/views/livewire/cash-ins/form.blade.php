<div class="max-w-xl mx-auto space-y-6">
    <x-page-header title="{{ $cashInId ? 'Ubah Kas Masuk' : 'Catat Kas Masuk Baru' }}"
                   description="{{ $cashInId ? 'Perbarui data catatan kas masuk toko' : 'Catat pemasukan kas seperti penjualan, pelunasan piutang, atau setoran' }}">
        <x-slot:actions>
            <a href="{{ route('cash-ins.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-line">
            <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                <x-icon name="plus-circle" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="font-semibold text-base text-text-main leading-tight">Rincian Kas Masuk</h2>
                <p class="text-xs text-text-secondary mt-0.5">Kas masuk akan tercatat dalam laporan keuangan toko</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="label">Akun Kas <span class="text-error">*</span></label>
                <select wire:model.live="cashAccountId" class="input @error('cashAccountId') border-error @enderror">
                    <option value="">-- Pilih Akun Kas --</option>
                    @foreach ($cashAccountOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @if ($cashAccountId && isset($cashAccountBalances[$cashAccountId]))
                    <p class="text-[11px] text-text-secondary mt-1">
                        Saldo saat ini: <span class="font-semibold text-text-main">Rp {{ number_format($cashAccountBalances[$cashAccountId], 0, ',', '.') }}</span>
                    </p>
                @endif
                @error('cashAccountId')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Tanggal Terima <span class="text-error">*</span></label>
                <input type="date"
                       wire:model="received_at"
                       class="input @error('received_at') border-error @enderror">
                @error('received_at')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Kategori <span class="text-error">*</span></label>
                <select wire:model="category" class="input @error('category') border-error @enderror">
                    <option value="penjualan">Penjualan</option>
                    <option value="piutang">Pelunasan Piutang</option>
                    <option value="setoran">Setoran</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                @error('category')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Deskripsi / Keterangan</label>
                <input type="text"
                       wire:model="description"
                       placeholder="Contoh: Pembayaran pelanggan, setoran kas"
                       class="input @error('description') border-error @enderror">
                @error('description')
                    <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Nominal Kas Masuk (Rp) <span class="text-error">*</span></label>
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
                <a href="{{ route('cash-ins.index') }}" wire:navigate class="btn-ghost px-4 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-6 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Kas Masuk</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>