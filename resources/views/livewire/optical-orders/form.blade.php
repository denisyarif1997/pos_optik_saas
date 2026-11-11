<div class="max-w-3xl mx-auto space-y-6">
    <x-page-header title="{{ $orderId ? 'Ubah Pesanan Kacamata' : 'Pesanan Kacamata Baru' }}"
                   description="{{ $orderId ? 'Perbarui data pesanan lensa laboratorium dan status pembayaran' : 'Catat order pembuatan kacamata resep dengan pengerjaan lab' }}">
        <x-slot:actions>
            <a href="{{ route('optical-orders.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl">
        <form wire:submit="save" class="space-y-6">

            <!-- Data Pemesan & Lab -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 mb-4 border-b border-line">
                    <div class="w-8 h-8 rounded-lg bg-emerald-bg text-emerald flex items-center justify-center font-bold text-xs">1</div>
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Data Pemesan &amp; Pengerjaan Lab</h2>
                        <p class="text-[11px] text-text-secondary">Pilih pelanggan dan tentukan lab pengerjaan lensa</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="label">Nama Pelanggan <span class="text-error">*</span></label>
                        <select wire:model.live="customer_id" class="input @error('customer_id') border-error @enderror">
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
                            <label class="label">Nama Lab / Supplier Lensa</label>
                            <input type="text"
                                   wire:model="lab_name"
                                   placeholder="Contoh: Lab Domas / Lab Utama"
                                   class="input @error('lab_name') border-error @enderror">
                            @error('lab_name')
                                <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Estimasi Selesai</label>
                            <input type="date"
                                   wire:model="estimated_done_at"
                                   class="input @error('estimated_done_at') border-error @enderror">
                            @error('estimated_done_at')
                                <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Frame dari Stok -->
            @if (!$orderId && $frames->count() > 0)
                <div>
                    <div class="flex items-center gap-2 pb-2.5 mb-3 border-b border-line">
                        <div class="w-8 h-8 rounded-lg bg-emerald-bg text-emerald flex items-center justify-center font-bold text-xs">2</div>
                        <div>
                            <h2 class="text-sm font-semibold text-text-main">Frame Kacamata dari Stok (Opsional)</h2>
                            <p class="text-[11px] text-text-secondary">Pilih bingkai kacamata yang diambil dari inventori toko</p>
                        </div>
                    </div>

                    <div class="p-3 bg-canvas rounded-xl border border-line max-h-52 overflow-y-auto sidebar-scroll grid sm:grid-cols-2 gap-2">
                        @foreach ($frames as $f)
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-line bg-surface hover:border-emerald-soft transition cursor-pointer">
                                <input type="checkbox"
                                       wire:model="frameIds"
                                       value="{{ $f->id }}"
                                       class="w-4 h-4 rounded text-emerald accent-emerald">
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-semibold text-text-main truncate">{{ $f->name }}</div>
                                    <div class="text-[10px] text-text-secondary">Rp {{ number_format($f->sell_price, 0, ',', '.') }} &middot; Stok: {{ $f->stock }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Nilai Transaksi & Uang Muka -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 mb-4 border-b border-line">
                    <div class="w-8 h-8 rounded-lg bg-emerald-bg text-emerald flex items-center justify-center font-bold text-xs">{{ (!$orderId && $frames->count() > 0) ? '3' : '2' }}</div>
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Nilai Biaya &amp; Uang Muka (DP)</h2>
                        <p class="text-[11px] text-text-secondary">Penentuan harga total pesanan dan pembayaran DP pelanggan</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Total Harga Pesanan (Rp) <span class="text-error">*</span></label>
                        <input type="number"
                               wire:model.live="total"
                               class="input @error('total') border-error @enderror"
                               placeholder="0">
                        @error('total')
                            <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Uang Muka / DP Dibayar (Rp) <span class="text-error">*</span></label>
                        <input type="number"
                               wire:model.live="dp_total"
                               class="input @error('dp_total') border-error @enderror"
                               placeholder="0">
                        @error('dp_total')
                            <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kalkulasi Sisa Piutang / Pelunasan -->
                <div class="mt-3 p-3.5 bg-emerald-bg/50 rounded-xl border border-emerald-soft flex items-center justify-between">
                    <div>
                        <span class="text-xs font-medium text-text-secondary">Sisa Tagihan yang Harus Dilunasi Saat Ambil:</span>
                        <div class="text-base font-bold text-emerald-dark">
                            Rp {{ number_format(max(0, (float)$total - (float)$dp_total), 0, ',', '.') }}
                        </div>
                    </div>
                    @if ((float)$total > 0 && (float)$dp_total >= (float)$total)
                        <span class="status-badge success">Lunas</span>
                    @elseif ((float)$dp_total > 0)
                        <span class="status-badge warning">DP Terbayar</span>
                    @else
                        <span class="status-badge info">Belum Ada DP</span>
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-line">
                <a href="{{ route('optical-orders.index') }}" wire:navigate class="btn-ghost px-5 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary px-7 py-2.5 text-sm font-semibold flex items-center gap-2">
                    <x-icon name="glasses" class="w-4 h-4" />
                    <span wire:loading.remove wire:target="save">Simpan Pesanan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
