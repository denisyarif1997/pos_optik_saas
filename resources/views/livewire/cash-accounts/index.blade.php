<div class="mx-auto max-w-5xl space-y-6">
    <div>
        <h1 class="text-xl font-bold text-text-main">Master Akun Kas</h1>
        <p class="mt-1 text-sm text-text-secondary">Kelola akun tujuan pembayaran. Halaman ini tidak mencatat saldo atau mutasi.</p>
    </div>

    @if ($statusMessage)
        <div class="alert {{ $statusType === 'error' ? 'alert-error' : 'alert-success' }}">{{ $statusMessage }}</div>
    @endif

    <section class="soft-card p-5">
        <h2 class="font-semibold text-text-main">{{ $cashAccountId ? 'Edit akun kas' : 'Tambah akun kas' }}</h2>
        <form wire:submit="save" class="mt-4 grid gap-4 sm:grid-cols-[1fr_200px_auto] sm:items-end">
            <div>
                <label for="cashAccountName" class="label">Nama akun</label>
                <input id="cashAccountName" type="text" wire:model="name" class="input" placeholder="Contoh: Kas Toko">
                @error('name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="cashAccountType" class="label">Jenis akun</label>
                <select id="cashAccountType" wire:model="type" class="input">
                    <option value="cash">Tunai</option>
                    <option value="bank">Bank</option>
                </select>
                @error('type') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">{{ $cashAccountId ? 'Simpan' : 'Tambah' }}</button>
                @if ($cashAccountId)
                    <button type="button" wire:click="cancelEditing" class="btn-ghost">Batal</button>
                @endif
            </div>
        </form>
    </section>

    <section class="soft-card overflow-hidden">
        <div class="border-b border-line px-5 py-4">
            <h2 class="font-semibold text-text-main">Daftar akun kas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="bg-canvas text-xs text-text-secondary">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Nama akun</th>
                        <th class="px-5 py-3 font-semibold">Jenis</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($accounts as $account)
                        <tr wire:key="cash-account-{{ $account->id }}">
                            <td class="px-5 py-3 font-medium text-text-main">{{ $account->name }}</td>
                            <td class="px-5 py-3 text-text-secondary">{{ $account->type === 'cash' ? 'Tunai' : 'Bank' }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $account->is_active ? 'bg-emerald-bg text-emerald-dark' : 'bg-canvas text-text-muted' }}">
                                    {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit({{ $account->id }})" class="btn-ghost px-3 py-1.5 text-xs">Edit</button>
                                    <button type="button" wire:click="toggleActive({{ $account->id }})" class="btn-ghost px-3 py-1.5 text-xs">
                                        {{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
