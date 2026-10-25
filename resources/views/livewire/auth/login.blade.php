<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="w-full max-w-md space-y-8">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Masuk</h2>
            <p class="mt-2 text-sm text-slate-500">Masuk untuk membuka kasir tokomu</p>
        </div>

        <div class="mt-8 space-y-6">
            <form wire:submit="login" class="space-y-4">
                <div>
                    <label class="label text-sm font-medium text-slate-600">Email</label>
                    <input
                        type="email"
                        wire:model="email"
                        class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400"
                        placeholder="nama@toko.com"
                    >
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label text-sm font-medium text-slate-600">Kata sandi</label>
                    <input
                        type="password"
                        wire:model="password"
                        class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400"
                        placeholder="*******"
                    >
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="checkbox" wire:model="remember">
                            <span>Ingat saya</span>
                        </label>
                        <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors lh-none">
                            Lupa password?
                        </a>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-lg bg-slate-900 px-6 py-3 shadow-sm text-lg font-medium text-white hover:bg-slate-800 hover:shadow-md transition-all duration-200">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-500">
            Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-slate-600 hover:text-slate-800 transition-colors" wire:navigate>Daftar</a>
        </p>
    </div>
</div>
