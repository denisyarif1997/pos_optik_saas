<div>
    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Daftar + buat tokomu 🏪</h2>
    <p class="mt-1 text-sm text-slate-500">Satu akun hanya untuk satu toko. Tanpa admin lintas-toko.</p>
    <form wire:submit="register" class="space-y-5">
        <div>
                <label class="label text-sm font-medium text-slate-600">Nama pemilik</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 placeholder-" placeholder="Nama lengkap">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        <div>
                <label class="label text-sm font-medium text-slate-600">Email</label>
                <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 placeholder-" placeholder="pemilik@tokomu.com">
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        <div>
                <label class="label text-sm font-medium text-slate-600">Password (min 8)</label>
                <input type="password" wire:model="password" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 placeholder-" placeholder="******">
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        <div class="bg-emerald-50/60 border border-emerald-100 rounded-lg p-4 space-y-4">
                <div>
                    <label class="label text-sm font-medium text-slate-600">Nama toko optik</label>
                    <input type="text" wire:model="store_name" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 placeholder-" placeholder="Optik Sejahtera">
                    @error('store_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label text-sm font-medium text-slate-600">No. HP toko (opsional)</label>
                    <input type="text" wire:model="store_phone" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 placeholder-" placeholder="08xx">
                </div>
            </div>
        <button class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold ">Daftar & Buat Toko →</button>
    </form>
    <p class="text-sm text-center mt-5 text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" wire:navigate class="text-emerald-700 font-bold">Masuk</a></p>
</div>


