<div class="space-y-4">
    <x-page-header title="Kategori" description="Kelola kategori produk toko Anda">
        <x-slot:actions>
            <button wire:click="showForm = true" class="btn-primary px-4 py-2 text-sm">+ Tambah Kategori</button>
        </x-slot:actions>
    </x-page-header>

    @if ($showForm)
        <div class="card p-6 mb-6 border-emerald-soft bg-emerald-bg/30 rounded-2xl shadow-card space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-emerald-soft">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald text-white flex items-center justify-center shadow-xs">
                        <x-icon name="tag" class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Tambah Kategori Baru</h2>
                        <p class="text-xs text-text-secondary">Kelompokkan produk agar mudah dicari</p>
                    </div>
                </div>
                <button wire:click="showForm = false" class="btn-ghost px-3 py-1.5 text-xs">Batal</button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="label">Nama Kategori <span class="text-error">*</span></label>
                    <input type="text" wire:model="name" class="input @error('name') border-error @enderror" placeholder="Contoh: Frame Pria, Lensa Bluechromic, Softlens">
                    @error('name') <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p> @enderror
                </div>

                <div class="p-3 bg-white/80 rounded-xl border border-line flex items-center justify-between">
                    <div>
                        <label for="active_cat" class="text-xs font-semibold text-text-main cursor-pointer">Status Kategori Aktif</label>
                        <p class="text-[11px] text-text-secondary">Kategori aktif dapat langsung digunakan untuk produk baru</p>
                    </div>
                    <input type="checkbox" wire:model="is_active" id="active_cat" class="w-4 h-4 rounded text-emerald accent-emerald cursor-pointer">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-emerald-soft">
                    <button type="button" wire:click="showForm = false" class="btn-ghost px-4 py-2 text-xs">Batal</button>
                    <button type="submit" class="btn-primary px-5 py-2 text-xs font-semibold">Simpan Kategori</button>
                </div>
            </form>
        </div>
    @endif

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($categories as $c)
            <div class="card p-4 hover:border-accent/50 transition">
                <div class="flex justify-between items-center mb-3">
                    <div class="font-semibold text-text-main truncate">{{ $c->name }}</div>
                    <x-status-badge :status="$c->is_active ? 'Aktif' : 'Nonaktif'" :class="$c->is_active ? 'success' : 'error'" />
                </div>
                <div class="text-[11px] text-text-secondary mb-3">{{ Str::slug($c->name) }}</div>
                <div class="flex items-center gap-2 pt-3 border-t border-line">
                    <button wire:click="toggle({{ $c->id }})"
                        class="flex-1 btn-ghost py-2 text-xs text-center">{{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    <a href="{{ route('categories.edit', $c->id) }}" wire:navigate
                        class="flex-1 btn-primary py-2 text-xs text-center">Ubah</a>
                </div>
            </div>
        @empty
            <div class="col-span-1 sm:col-span-2 xl:col-span-3">
                <x-empty-state title="Belum ada kategori."
                    description="Tambahkan kategori pertama untuk mengelompokkan produk toko Anda."
                    actionHref="{{ route('categories.index') }}" actionLabel="Tambah Kategori" />
            </div>
        @endforelse
    </div>
</div>


