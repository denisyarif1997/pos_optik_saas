<div class="space-y-4">
    <x-page-header title="Kategori" description="Kelola kategori produk toko Anda">
        <x-slot:actions>
            <!-- <button wire:click="showForm = true" class="btn-primary px-4 py-2 text-sm">+ Tambah Kategori</button> -->
                        <a href="{{ route('categories.create') }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Tambah Kategori Produk</a>

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

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">Nama Kategori</th>
                        <th class="py-3 px-4 text-left w-32">Slug</th>
                        <th class="py-3 px-4 text-left w-20">Status</th>
                        <th class="py-3 px-4 text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($categories as $c)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-text-main">{{ $c->name }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ Str::slug($c->name) }}</td>
                            <td class="py-3 px-4">
                                <x-status-badge :status="$c->is_active ? 'Aktif' : 'Nonaktif'" :class="$c->is_active ? 'success' : 'error'" />
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="toggle({{ $c->id }})"
                                        class="text-[11px] font-semibold {{ $c->is_active ? 'text-error' : 'text-success' }} hover:opacity-70">{{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    <a href="{{ route('categories.edit', $c->id) }}" wire:navigate
                                        class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Ubah</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-sm">Belum ada kategori. Tambahkan kategori pertama untuk mengelompokkan produk toko Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


