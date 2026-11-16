<div class="max-w-7xl mx-auto space-y-6">
    <x-page-header title="Pengaturan Toko"
                   description="Kelola informasi identitas toko, nomor kontak, alamat, catatan nota, dan logo resmi" />

    <div class="grid lg:grid-cols-12 gap-6 items-start">
        <!-- Informasi Profil Toko -->
        <div class="lg:col-span-8 card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-line">
                <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                    <x-icon name="store" class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="font-semibold text-base text-text-main leading-tight">Profil &amp; Kontak Toko</h2>
                    <p class="text-xs text-text-secondary mt-0.5">Informasi ini akan tercetak pada struk penjualan dan nota pesanan kacamata</p>
                </div>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="label">Nama Toko <span class="text-error">*</span></label>
                        <input type="text"
                               wire:model="name"
                               placeholder="Contoh: Optik Sejahtera"
                               class="input @error('name') border-error @enderror" />
                        @error('name')
                            <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="label">Nomor Telepon / WhatsApp</label>
                        <input type="text"
                               wire:model="phone"
                               placeholder="08123456789"
                               class="input @error('phone') border-error @enderror" />
                        @error('phone')
                            <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="label">Alamat Lengkap Toko</label>
                        <textarea wire:model="address"
                                  rows="3"
                                  placeholder="Jl. Ahmad Yani No. 45, Kebayoran Baru, Jakarta Selatan"
                                  class="input @error('address') border-error @enderror"></textarea>
                        @error('address')
                            <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="label">Catatan Kaki Struk / Nota (Receipt Note)</label>
                        <textarea wire:model="receipt_note"
                                  rows="2"
                                  placeholder="Contoh: Terima kasih atas kunjungan Anda. Kacamata yang sudah dibeli dapat diservis gratis 1 tahun."
                                  class="input @error('receipt_note') border-error @enderror"></textarea>
                        @error('receipt_note')
                            <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                    <a href="{{ route('dashboard') }}" wire:navigate class="btn-ghost px-5 py-2.5 text-sm font-medium">Kembali</a>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="btn-primary px-7 py-2.5 text-sm font-semibold flex items-center gap-2">
                        <x-icon name="check" class="w-4 h-4" />
                        <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Logo Toko Card -->
        <div class="lg:col-span-4 card p-6 bg-surface border border-line shadow-card rounded-2xl space-y-5 sticky top-20">
            <div class="flex items-center gap-3 pb-3 border-b border-line">
                <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                    <x-icon name="box" class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="font-semibold text-base text-text-main leading-tight">Logo Toko</h2>
                    <p class="text-xs text-text-secondary mt-0.5">Format PNG, JPG maks 2MB</p>
                </div>
            </div>

            @if ($existingLogo)
                <div class="flex flex-col items-center gap-3 p-4 bg-canvas rounded-xl border border-line">
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($existingLogo) }}"
                         alt="Logo Toko"
                         class="h-28 w-28 object-contain rounded-xl bg-white p-2 border border-line shadow-xs">
                    <button wire:click="removeLogo"
                            wire:confirm="Yakin ingin menghapus logo toko?"
                            class="text-xs font-semibold text-error hover:underline flex items-center gap-1">
                        <x-icon name="x" class="w-3.5 h-3.5" />
                        Hapus Logo Saat Ini
                    </button>
                </div>
            @else
                <div class="h-32 rounded-xl border-2 border-dashed border-line flex flex-col items-center justify-center text-center p-4 bg-canvas/50">
                    <x-icon name="store" class="w-8 h-8 text-text-muted mb-1" />
                    <span class="text-xs font-medium text-text-secondary">Belum ada logo toko</span>
                    <span class="text-[10px] text-text-muted mt-0.5">Logo akan ditampilkan pada kop nota</span>
                </div>
            @endif

            <div class="space-y-3">
                <div>
                    <label class="label">Pilih Berkas Logo Baru</label>
                    <input type="file"
                           wire:model="logo"
                           accept="image/*"
                           class="w-full text-xs text-text-secondary file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-bg file:text-emerald-dark hover:file:bg-emerald-soft file:cursor-pointer cursor-pointer border border-line rounded-lg p-1.5 bg-canvas">
                    @error('logo')
                        <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="logo" class="text-xs text-emerald font-medium mt-1.5 flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald animate-ping"></span>
                        Mengunggah gambar...
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>