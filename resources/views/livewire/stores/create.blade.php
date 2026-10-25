<div class="space-y-5">
    @if ($store)
        <div class="relative overflow-hidden bg-slate-900 text-white rounded-xl p-6 sm:p-8">
            <div class="font-semibold text-xl">{{ $store->name }}</div>
            <p class="text-emerald-50 text-sm mt-1">{{ $store->address ?? 'Alamat belum dilengkapi' }} • {{ $store->phone ?? '-' }}</p>
            <div class="mt-4 inline-flex text-xs px-3 py-1.5 rounded-full bg-white/20">1 akun terkunci ke toko ini • Role: {{ $store->pivot->role }}</div>
            <svg viewBox="0 0 400 80" class="absolute -bottom-1 left-0 w-full opacity-25" preserveAspectRatio="none"><path d="M0,48 C80,80 160,10 240,40 C320,70 360,30 400,50 L400,80 L0,80 Z" fill="#ffffff"/></svg>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-sm text-slate-500">
            Akun ini hanya mengelola <b class="text-slate-700">{{ $store->name }}</b>. Tidak bisa membuat, melihat, atau pindah ke toko lain. Tidak ada peran admin lintas-toko.
        </div>
    @else
        <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8">
            <h2 class="text-xl font-semibold tracking-tight">Lengkapi profil tokomu 🏪</h2>
            <p class="text-sm text-slate-500 mb-5">Satu akun, satu toko. Kamu otomatis jadi pemilik.</p>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="text-sm font-semibold">Nama toko</label>
                    <input type="text" wire:model="name" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 outline-none focus:ring-2 focus:ring-emerald-300" placeholder="Optik Maju">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">No. HP (opsional)</label>
                    <input type="text" wire:model="phone" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="text-sm font-semibold">Alamat (opsional)</label>
                    <textarea wire:model="address" rows="3" class="mt-1.5 w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 outline-none focus:ring-2 focus:ring-emerald-300"></textarea>
                </div>
                <button class="w-full sm:w-auto bg-slate-900 hover:bg-black text-white text-sm font-semibold ">Simpan Toko →</button>
            </form>
        </div>
    @endif
</div>


