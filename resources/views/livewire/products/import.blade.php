<div class="max-w-3xl mx-auto space-y-6">
    <x-page-header title="Impor Produk" description="Impor data produk & lensa dari file CSV (buka di Excel)">
        <x-slot:actions>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
            <a href="#" wire:click="downloadTemplate" class="btn-primary px-4 py-2 text-sm">⬇ Unduh Template CSV</a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl">
        {{-- Tahap 1: Upload file --}}
        <div wire:loading.class="opacity-60" class="transition-opacity">
            <div class="space-y-5">
                <div class="border border-line rounded-xl p-5 bg-canvas">
                    <h3 class="text-sm font-semibold text-text-main mb-2">1. Pilih File CSV</h3>
                    <input type="file" wire:model="file" accept=".csv,.txt" class="block w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald file:text-emerald-dark hover:file:bg-emerald-hover">
                    <p class="text-[11px] text-text-secondary mt-1">Format: CSV. Buka template di Excel, isi data, lalu simpan sebagai CSV (UTF-8).</p>
                    @error('file')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                    <button wire:click="upload" wire:loading.attr="disabled" class="btn-primary px-5 py-2.5 text-sm font-semibold">
                        Muat File
                    </button>
                </div>

                {{-- Tahap 2: Validasi data master --}}
                @if ($validationStage === 1 && count($fileContent) > 0)
                    <div class="border border-line rounded-xl p-5 bg-surface">
                        <h3 class="text-sm font-semibold text-text-main mb-2">2. Validasi Data Master</h3>
                        <p class="text-[11px] text-text-secondary mb-4">Memeriksa tipe produk dan kategori yang ada pada file terhadap master toko Anda.</p>

                        @if (count($validationWarnings) > 0)
                            <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 mb-4">
                                <p class="text-xs font-semibold text-amber-800 mb-1">Perhatian: Data master belum ada</p>
                                <ul class="text-[11px] text-amber-700 space-y-1">
                                    @foreach ($validationWarnings as $warning)
                                        <li>{{ $warning }}</li>
                                    @endforeach
                                </ul>
                                <p class="text-[11px] text-amber-700 mt-2">Silakan tambahkan kategori yang belum ada melalui menu <em>Kategori</i> terlebih dahulu, lalu ulangi pengunggahan file.</p>
                            </div>
                        @endif

                        @if (count($validationErrors) > 0)
                            <div class="bg-error/10 border border-error/20 rounded-lg p-3 mb-4">
                                <p class="text-xs font-semibold text-error mb-1">Kesalahan Validasi</p>
                                <ul class="text-[11px] text-error space-y-1">
                                    @foreach ($validationErrors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (count($validationErrors) === 0)
                            <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-3 mb-4">
                                <p class="text-xs font-semibold text-emerald-800">Validasi berhasil. Semua tipe dan kategori sudah sesuai dengan data master toko.</p>
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-3">
                            <button wire:click="resetImport" class="btn-ghost px-5 py-2.5 text-sm font-medium text-text-secondary">Ulangi</button>
                            @if (count($validationErrors) === 0)
                                <button wire:click="continueImport" class="btn-primary px-6 py-2.5 text-sm font-semibold">
                                    <span>➜ Lanjutkan Upload</span>
                                </button>
                            @else
                                <button disabled class="px-6 py-2.5 text-sm font-semibold text-slate-400 bg-slate-100 border-slate-200 cursor-not-allowed">Perbaiki Error Dulu</button>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Tahap 3: Hasil impor --}}
                @if ($showImportResult)
                    <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4 mb-4">
                        <h3 class="text-sm font-semibold text-emerald-800 mb-2">Impor Selesai</h3>
                        <p class="text-[11px] text-emerald-700">{{ $importedCount }} produk berhasil diimpor / diperbarui.</p>
                        <button wire:click="resetImport" class="btn-ghost px-5 py-2.5 text-sm font-medium mt-3">Impor Lagi</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>