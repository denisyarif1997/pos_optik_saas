<div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <div>
            <h1 class="font-semibold text-lg">Pengaturan Toko</h1>
            <p class="text-xs text-slate-500">Nama, kontak, alamat, dan catatan nota.</p>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-xs font-semibold text-slate-600">Nama Toko</label>
                <input type="text" wire:model="name" class="mt-1 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600">Telepon</label>
                <input type="text" wire:model="phone" class="mt-1 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600">Alamat</label>
                <textarea wire:model="address" rows="3" class="mt-1 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300"></textarea>
                @error('address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600">Catatan Nota</label>
                <textarea wire:model="receipt_note" rows="2" placeholder="Terima kasih telah berbelanja..." class="mt-1 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300"></textarea>
                @error('receipt_note') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-slate-900 hover:bg-black text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <div>
            <h2 class="font-semibold">Logo Toko</h2>
            <p class="text-xs text-slate-500">Maks 2MB. Format gambar.</p>
        </div>
        @if ($existingLogo)
            <div class="flex flex-col items-center gap-2">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($existingLogo) }}" alt="Logo" class="h-24 w-24 object-contain rounded-lg border border-slate-200">
                <button wire:click="removeLogo" class="text-xs font-bold text-red-600">Hapus Logo</button>
            </div>
        @else
            <div class="h-24 w-24 mx-auto rounded-lg border border-dashed border-slate-300 flex items-center justify-center text-xs text-slate-400">Belum ada logo</div>
        @endif
        <div>
            <input type="file" wire:model="logo" accept="image/*" class="w-full text-sm">
            @error('logo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            <div wire:loading wire:target="logo" class="text-xs text-slate-500 mt-1">Mengunggah...</div>
        </div>
        @if ($logo)
            <button wire:click="save" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">Upload Logo</button>
        @endif
    </div>
</div>
'@