<div>
    <x-page-header title="{{ $customerId ? 'Ubah Pelanggan' : 'Tambah Pelanggan' }}"
        description="{{ $customerId ? 'Perbarui data pelanggan existentes' : 'Tambah pelanggan baru ke database toko Anda' }}">
        <a href="{{ route('customers.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Kembali</a>
    </x-page-header>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="label block mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
            <input type="text" wire:model="name" class="input"
                placeholder="Contoh: Budi Santoso">
            @error('name') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="label block mb-1.5">Nomor HP</label>
                <input type="text" wire:model="phone" class="input"
                    placeholder="08123456789">
                @error('phone') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label block mb-1.5">Tanggal Lahir</label>
                <input type="date" wire:model="birth_date" class="input">
                @error('birth_date') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="label block mb-1.5">Alamat</label>
            <textarea wire:model="address" rows="3" class="input"
                placeholder="Jl. Contoh No. 123, Kota"></textarea>
            @error('address') <p class="text-[10px] text-error mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-line">
            <a href="{{ route('customers.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Batal</a>
            <button type="submit" class="btn-primary px-6 py-2 text-sm font-semibold">Simpan</button>
        </div>
    </form>
</div>


