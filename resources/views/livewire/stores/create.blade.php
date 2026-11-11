<div class="max-w-2xl mx-auto space-y-6">
    @if ($store)
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald to-emerald-dark text-white rounded-2xl p-6 sm:p-8 shadow-soft">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-xs flex items-center justify-center">
                    <x-icon name="store" class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h1 class="font-bold text-xl leading-tight">{{ $store->name }}</h1>
                    <p class="text-emerald-100 text-xs">{{ $store->phone ?? 'Nomor telepon belum diatur' }}</p>
                </div>
            </div>
            <p class="text-emerald-50 text-xs mt-2">{{ $store->address ?? 'Alamat toko belum diatur' }}</p>
            <div class="mt-5 inline-flex items-center gap-2 text-xs px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-xs font-medium">
                <span>Peran: <strong class="capitalize">{{ $store->pivot->role }}</strong></span>
                <span>&middot;</span>
                <span>1 Akun Terkunci ke Toko Ini</span>
            </div>
        </div>

        <div class="card p-6 bg-surface border border-line shadow-card rounded-2xl text-xs text-text-secondary leading-relaxed">
            Akun Anda terdaftar mengelola toko <strong class="text-text-main">{{ $store->name }}</strong>. Untuk mengubah alamat, nomor telepon, atau logo toko, silakan kunjungi menu <a href="{{ route('settings.store') }}" wire:navigate class="text-emerald font-semibold hover:underline">Profil Toko</a>.
        </div>
    @else
        <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-line">
                <div class="w-12 h-12 rounded-2xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                    <x-icon name="store" class="w-6 h-6" />
                </div>
                <div>
                    <h1 class="text-lg font-bold text-text-main leading-tight">Lengkapi Profil Toko Baru Anda</h1>
                    <p class="text-xs text-text-secondary mt-0.5">Satu akun untuk satu toko optik. Anda otomatis menjadi pemilik (owner).</p>
                </div>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="label">Nama Toko Optik <span class="text-error">*</span></label>
                    <input type="text"
                           wire:model="name"
                           placeholder="Contoh: Optik Sejahtera Mandiri"
                           class="input @error('name') border-error @enderror">
                    @error('name')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Nomor WhatsApp / HP Toko</label>
                    <input type="text"
                           wire:model="phone"
                           placeholder="08123456789"
                           class="input @error('phone') border-error @enderror">
                    @error('phone')
                        <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Alamat Toko</label>
                    <textarea wire:model="address"
                              rows="3"
                              placeholder="Jl. Raya No. 123, Kota"
                              class="input @error('address') border-error @enderror"></textarea>
                    @error('address')
                        <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3 border-t border-line flex justify-end">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="btn-primary w-full sm:w-auto px-7 py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="save">Simpan &amp; Buat Toko</span>
                        <span wire:loading wire:target="save">Membuat Toko...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
