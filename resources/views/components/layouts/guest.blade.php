<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? "Masuk" }}</title><link rel=\"icon\" type=\"image/svg+xml\" href=\"/images/logo.svg\"/><link rel=\"icon\" type=\"image/x-icon\" href=\"/favicon.ico\"/><link rel=\"apple-touch-icon\" href=\"/images/logo.svg\"/><link rel=\"manifest\" href=\"/manifest.json\"/>@vite(["resources/css/app.css", "resources/js/app.js"])@livewireStyles</head>
<body class="min-h-screen antialiased bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 flex items-center justify-center p-4"><div class="w-full max-w-2xl lg:max-w-4xl space-y-8">
        <!-- Branding Section (visible on lg+) -->
        <div class="hidden lg:flex flex-col justify-between bg-slate-900 text-white p-10">
            <div class="flex items-center gap-2"><img src="/images/logo.svg" alt="Optik App Logo" class="h-8 w-auto"/><span class="text-sm font-semibold tracking-wide">OPTIK &middot; KASIR</span></div>
<div><h2 class="text-2xl font-semibold leading-snug">Pembukuan toko optik<br>yang rapi dan cepat.</h2><p class="mt-2 text-sm text-slate-300">Kasir, stok, pelanggan, resep, dan pesanan dalam satu tempat.</p></div>
<div class="text-xs text-slate-400">Satu akun untuk satu toko.</div>
        </div>

        <!-- Login Card -->
        <div class="lg:flex items-center justify-center p-6">
            <div class="w-full max-w-sm lg:w-96 lg:p-8 lg:pl-10">
                <div class="rounded-2xl bg-white p-8 shadow-xl shadow-slate-200/40">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>@livewireScripts</body></html>
