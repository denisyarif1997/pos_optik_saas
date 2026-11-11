<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Manajemen Staf & Hak Akses"
                   description="Kelola anggota tim toko, tentukan peran otorisasi, dan atur status akses akun">
        <x-slot:actions>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-bg text-xs font-semibold text-emerald-dark border border-emerald-soft">
                <x-icon name="users" class="w-4 h-4" />
                <span>Total Staf: {{ $staff->count() }}</span>
            </span>
        </x-slot:actions>
    </x-page-header>

    <!-- Main Grid Layout -->
    <div class="grid lg:grid-cols-12 gap-6 items-start">

        <!-- ========================================== -->
        <!-- KOLOM KIRI: Daftar Staf Toko (Tabel Card) -->
        <!-- ========================================== -->
        <div class="lg:col-span-7 xl:col-span-8 card p-5 sm:p-6 bg-surface border border-line shadow-card rounded-2xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-base text-text-main">Daftar Staf Toko</h2>
                    <p class="text-xs text-text-secondary mt-0.5">Semua pengguna yang terdaftar pada toko ini</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-line">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-canvas border-b border-line text-[11px] font-bold text-text-secondary uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Nama Staf</th>
                            <th class="py-3 px-4">Role / Peran</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line bg-surface">
                        @forelse ($staff as $s)
                            <tr class="hover:bg-canvas/50 transition-colors">
                                <!-- Nama & Email -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-emerald text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                            {{ strtoupper(substr($s->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-text-main flex items-center gap-1.5 truncate">
                                                <span>{{ $s->name }}</span>
                                                @if ($s->id === $currentUserId)
                                                    <span class="px-1.5 py-0.5 text-[10px] font-semibold bg-emerald-bg text-emerald-dark border border-emerald-soft rounded">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-text-secondary truncate mt-0.5">{{ $s->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role / Peran -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if ($editingUserId === $s->id)
                                        <div class="flex items-center gap-1.5">
                                            <select wire:model="editingRole" class="input py-1 px-2.5 text-xs w-auto">
                                                @foreach ($roles as $r)
                                                    <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                                                @endforeach
                                            </select>
                                            <button wire:click="saveRole"
                                                    class="btn-primary py-1 px-2.5 text-xs font-semibold rounded-lg">
                                                Simpan
                                            </button>
                                            <button wire:click="cancelEditRole"
                                                    class="btn-ghost py-1 px-2 text-xs rounded-lg">
                                                Batal
                                            </button>
                                        </div>
                                    @else
                                        @php
                                            $roleBadge = match($s->pivot->role) {
                                                'owner' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'kasir' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                'optician' => 'bg-emerald-bg text-emerald-dark border-emerald-soft',
                                                'stok' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                default => 'bg-canvas text-text-secondary border-line',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleBadge }} capitalize">
                                            {{ $s->pivot->role }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if ($s->pivot->is_active)
                                        <span class="status-badge success">Aktif</span>
                                    @else
                                        <span class="status-badge error">Nonaktif</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if ($editingUserId !== $s->id)
                                            <button wire:click="startEditRole({{ $s->id }})"
                                                    title="Ubah Role"
                                                    class="btn-ghost py-1.5 px-2.5 text-xs text-text-main hover:text-emerald rounded-lg flex items-center gap-1">
                                                <x-icon name="settings" class="w-3.5 h-3.5" />
                                                <span class="hidden sm:inline">Role</span>
                                            </button>
                                        @endif

                                        @if ($s->id !== $currentUserId)
                                            <button wire:click="toggleActive({{ $s->id }})"
                                                    class="py-1.5 px-2.5 text-xs font-medium rounded-lg border transition {{ $s->pivot->is_active ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-dark hover:bg-emerald-bg' }}">
                                                {{ $s->pivot->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>

                                            <button wire:click="resetPassword({{ $s->id }})"
                                                    wire:confirm="Reset password akun ini ke 'password123'?"
                                                    title="Reset Password"
                                                    class="btn-ghost py-1.5 px-2 text-xs text-text-secondary hover:text-error rounded-lg">
                                                Reset PW
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-sm text-text-secondary">
                                    Belum ada staf terdaftar di toko ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- KOLOM KANAN: Form Tambah Staf Baru        -->
        <!-- ========================================== -->
        <div class="lg:col-span-5 xl:col-span-4 card p-5 sm:p-6 bg-surface border border-line shadow-card rounded-2xl sticky top-20 space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-line">
                <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                    <x-icon name="plus" class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="font-semibold text-base text-text-main leading-tight">Tambah Staf Baru</h2>
                    <p class="text-xs text-text-secondary mt-0.5">Daftarkan akun tim untuk toko ini</p>
                </div>
            </div>

            <form wire:submit="addStaff" class="space-y-4">
                <!-- Nama Lengkap -->
                <div>
                    <label class="label">Nama Lengkap <span class="text-error">*</span></label>
                    <input type="text"
                           wire:model="name"
                           placeholder="Contoh: Siti Rahmawati"
                           class="input @error('name') border-error @enderror" />
                    @error('name')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="label">Alamat Email <span class="text-error">*</span></label>
                    <input type="email"
                           wire:model="email"
                           placeholder="staf@optik.com"
                           class="input @error('email') border-error @enderror" />
                    @error('email')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Awal -->
                <div>
                    <label class="label">Password <span class="text-error">*</span></label>
                    <input type="password"
                           wire:model="password"
                           placeholder="Minimal 6 karakter"
                           class="input @error('password') border-error @enderror" />
                    @error('password')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hak Akses / Role -->
                <div>
                    <label class="label">Hak Akses / Role <span class="text-error">*</span></label>
                    <select wire:model="role" class="input @error('role') border-error @enderror">
                        <option value="kasir">Kasir (Transaksi &amp; POS)</option>
                        <option value="optician">Optician (Pemeriksaan &amp; Lab)</option>
                        <option value="stok">Stok (Inventori &amp; Pembelian)</option>
                        <option value="owner">Owner (Akses Penuh)</option>
                    </select>
                    @error('role')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary w-full py-2.5 text-sm font-semibold flex items-center justify-center gap-2 mt-2">
                    <span wire:loading.remove wire:target="addStaff" class="flex items-center gap-2">
                        <x-icon name="plus" class="w-4 h-4" />
                        Tambah Staf Baru
                    </span>
                    <span wire:loading wire:target="addStaff" class="flex items-center gap-2">
                        Menyimpan...
                    </span>
                </button>
            </form>

            <!-- Role Permissions Guide -->
            <div class="mt-4 pt-4 border-t border-line space-y-2">
                <div class="text-[10px] font-bold text-text-secondary uppercase tracking-wider">Panduan Hak Akses:</div>
                <div class="grid grid-cols-1 gap-2 text-xs text-text-secondary">
                    <div class="flex items-start gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-purple-500 mt-1.5 flex-shrink-0"></span>
                        <span><strong class="text-text-main">Owner:</strong> Akses penuh seluruh fitur, laporan, dan profil toko.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-sky-500 mt-1.5 flex-shrink-0"></span>
                        <span><strong class="text-text-main">Kasir:</strong> Kasir POS, riwayat transaksi, retur, dan garansi.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mt-1.5 flex-shrink-0"></span>
                        <span><strong class="text-text-main">Optician:</strong> Pesanan kacamata, resep pelanggan, servis lab &amp; QC.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mt-1.5 flex-shrink-0"></span>
                        <span><strong class="text-text-main">Stok:</strong> Katalog produk, opname, stok barang, supplier &amp; pembelian.</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>