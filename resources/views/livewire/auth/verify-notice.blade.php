<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - {{ config('app.name', 'OptikGo') }}</title>
    <link rel="icon" type="image/svg+xml" href="/images/logo.svg"/>
    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
    <link rel="apple-touch-icon" href="/images/logo.svg"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen antialiased bg-canvas flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-surface border border-line shadow-card rounded-2xl p-8 sm:p-10 text-center">
            <div class="w-12 h-12 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center mx-auto mb-6">
                <x-icon name="mail" class="w-6 h-6" />
            </div>

            <h2 class="text-xl font-semibold text-text-main mb-2">Verifikasi Email</h2>
            <p class="text-text-secondary text-sm mb-6">
                Kami telah mengirimkan tautan verifikasi ke alamat email Anda.
                Silakan cek inbox dan klik tautan tersebut untuk mengaktivasi akun.
            </p>

            @if (session('status'))
                <div class="mb-6 p-3 rounded-lg bg-emerald-bg text-emerald-dark text-xs font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.resend') }}" class="space-y-3">
                @csrf
                <button type="submit"
                        class="btn-ghost w-full py-2 text-sm font-medium text-text-main hover:text-emerald transition-colors">
                    Kirim ulang tautan verifikasi
                </button>
            </form>

            <p class="mt-6 text-xs text-text-muted">
                Tidak menerima email?
                <a href="{{ route('verification.resend') }}" class="font-semibold text-emerald hover:text-emerald-dark">
                    Minta kirim ulang
                </a>
            </p>
        </div>
    </div>
    @livewireScripts
</body>
</html>