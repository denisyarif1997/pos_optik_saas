<div class="space-y-4">
    <x-page-header title="Kategori" description="Kelola kategori produk toko Anda">
        <button wire:click="showForm = true" class="btn-primary px-4 py-2 text-sm">+ Tambah Kategori</button>
    </x-page-header>

    @if ($showForm)
        <div class="card p-4 mb-4 border-accent bg-accent/5">
            <x-page-header title="Tambah Kategori" description="Tambah kategori baru untuk mengelompokkan produk">
                <button wire:click="showForm = false" class="btn-ghost px-4 py-2 text-sm">Batal</button>
            </x-page-header>

            <form wire:submit="save" class="space-y-3">
                <div>
                    <label class="label block mb-1.5">Nama Kategori <span class="text-error">*</span></label>
                    <input type="text" wire:model="name" class="input" placeholder="Contoh: Frame, Lensa, Softlens">
                    @error('name') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" wire:model="is_active" id="active" class="accent">
                    <label for="active" class="text-[11px] font-medium text-text-main">Aktif</label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-line">
                    <a href="{{ route('categories.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Batal</a>
                    <button type="submit" class="btn-primary px-6 py-2 text-sm font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($categories as $c)
            <div class="card p-4 hover:border-accent/50 transition">
                <div class="flex justify-between items-center mb-3">
                    <div class="font-semibold text-text-main truncate">{{ $c->name }}</div>
                    <x-status-badge :status="\$c->is_active ? 'Aktif' : 'Nonaktif'" :class="\$c->is_active ? 'success' : 'error'" />
                </div>
                <div class="text-[11px] text-text-secondary mb-3">{{ Str::slug(\$c->name) }}</div>
                <div class="flex items-center gap-2 pt-3 border-t border-line">
                    <button wire:click="toggle({{ \$c->id }})"
                        class="flex-1 btn-ghost py-2 text-xs text-center">{{ \$c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    <a href="{{ route('categories.edit', \$c->id) }}" wire:navigate
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


