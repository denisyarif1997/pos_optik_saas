<div class="min-h-screen antialiased bg-canvas flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="card p-8 bg-surface border border-line shadow-card rounded-2xl">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-bg text-emerald mx-auto mb-6">
                <x-icon name="package" class="w-6 h-6" />
            </div>

            <h2 class="text-xl font-semibold text-text-main mb-2 text-center">Lacak Pesanan Anda</h2>
            <p class="text-text-secondary text-sm text-center mb-6">
                Masukkan kode pesanan untuk melihat status pemesanan kacamata Anda.
            </p>

            @if (session('error'))
                <div class="mb-4 p-3 rounded-lg bg-error-bg text-error text-xs flex items-center gap-2">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" action="{{ route('track.form') }}" class="space-y-4">
                <div>
                    <label class="label">Kode Pesanan</label>
                    <input type="text"
                           name="code"
                           value="{{ old('code', request('code')) }}"
                           placeholder="Contoh: OPT-20251028-0001"
                           class="input w-full"
                           required>
                    @error('code')
                        <p class="text-[11px] text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="btn-primary w-full py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                    <x-icon name="search" class="w-4 h-4" />
                    Lacak Pesanan
                </button>
            </form>

            <p class="mt-4 text-xs text-text-secondary text-center">
                Kode pesanan terdapat pada struk/nota pembelian Anda.
            </p>
        </div>
    </div>
</div>
