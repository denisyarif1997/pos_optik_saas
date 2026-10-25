<div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <h1 class="font-semibold text-lg mb-1">Manajemen Staf</h1>
        <p class="text-xs text-slate-500 mb-3">Tambah, ubah role, dan nonaktifkan staf toko.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left table-head">
                    <th class="py-2 pr-3">Nama</th><th class="pr-3">Email</th><th class="pr-3">Role</th><th class="pr-3">Status</th><th class="pr-3 text-right">Aksi</th>
                </tr></thead>
                <tbody>
                @forelse ($staff as $s)
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="py-2 pr-3 font-bold">{{ $s->name }}@if($s->id === $currentUserId) <span class="text-[10px] text-emerald-600">(Anda)</span>@endif</td>
                        <td class="pr-3 text-slate-600">{{ $s->email }}</td>
                        <td class="pr-3">
                            @if ($editingUserId === $s->id)
                                <div class="flex items-center gap-1">
                                    <select wire:model="editingRole" class="rounded bg-slate-50 border border-emerald-100 px-2 py-1 text-xs">
                                        @foreach ($roles as $r)<option value="{{ $r }}">{{ ucfirst($r) }}</option>@endforeach
                                    </select>
                                    <button wire:click="saveRole" class="text-xs font-bold text-emerald-700">OK</button>
                                    <button wire:click="cancelEditRole" class="text-xs font-bold text-slate-500">X</button>
                                </div>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 capitalize">{{ $s->pivot->role }}</span>
                            @endif
                        </td>
                        <td class="pr-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $s->pivot->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $s->pivot->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="pr-3 text-right space-x-2">
                            @if ($editingUserId !== $s->id)<button wire:click="startEditRole({{ $s->id }})" class="text-xs font-bold text-emerald-700">Role</button>@endif
                            @if ($s->id !== $currentUserId)
                                <button wire:click="toggleActive({{ $s->id }})" class="text-xs font-bold {{ $s->pivot->is_active ? 'text-amber-600' : 'text-emerald-700' }}">{{ $s->pivot->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                <button wire:click="resetPassword({{ $s->id }})" class="text-xs font-bold text-slate-500" onclick="return confirm('Reset password staf ini ke password123?')">Reset PW</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-6 text-center text-sm text-slate-400">Belum ada staf.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-1">Tambah Staf Baru</h2>
        <p class="text-xs text-slate-500 mb-4">Buat akun baru dan lampirkan ke toko ini.</p>
        <form wire:submit="addStaff" class="space-y-3">
            <div><input type="text" wire:model="name" placeholder="Nama lengkap" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">@error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror</div>
            <div><input type="email" wire:model="email" placeholder="Email" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">@error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror</div>
            <div><input type="password" wire:model="password" placeholder="Password (min 6)" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">@error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror</div>
            <div><select wire:model="role" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">@foreach ($roles as $r)<option value="{{ $r }}">{{ ucfirst($r) }}</option>@endforeach</select>@error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror</div>
            <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg">+ Tambah Staf</button>
        </form>
        <div class="mt-4 text-[11px] text-slate-500 leading-relaxed">Role: <b>owner</b> (akses penuh), <b>stok</b> (produk/pembelian/stok), <b>kasir</b> (penjualan/retur), <b>optician</b> (pesanan/lab).</div>
    </div>
</div>
'@