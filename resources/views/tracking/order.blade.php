@php
    use Illuminate\Support\Str;

    $statuses = [
        'baru'          => ['label' => 'Pesanan Diterima', 'desc' => 'Pesanan Anda telah diterima dan akan segera diproses.'],
        'diproses'      => ['label' => 'Sedang Diproses', 'desc' => 'Tim kami sedang mempersiapkan frame dan lensa.'],
        'menunggu_lensa' => ['label' => 'Menunggu Lensa', 'desc' => 'Frame siap, menunggu kedatangan lensa dari supplier.'],
        'siap_diambil'  => ['label' => 'Siap Diambil', 'desc' => 'Pesanan siap, silakan ambil ke toko.'],
        'selesai'       => ['label' => 'Pesanan Selesai', 'desc' => 'Pesanan telah selesai dan siap digunakan.'],
        'diambil'       => ['label' => 'Sudah Diambil', 'desc' => 'Pesanan telah diambil oleh pemesan.'],
        'dibatalkan'    => ['label' => 'Dibatalkan', 'desc' => 'Pesanan ini telah dibatalkan.'],
    ];

    $activeStatuses = ['baru', 'diproses', 'menunggu_lensa', 'siap_diambil', 'selesai', 'diambil'];
    $currentStatus = $order->status;
    $currentIdx = array_search($currentStatus, $activeStatuses);
    $isCanceled = $currentStatus === 'dibatalkan';
    $isCompleted = in_array($currentStatus, ['selesai', 'diambil'], true);

    $storePhone = $order->store?->phone ?? null;
    if ($storePhone) {
        $waNumber = preg_replace('/[^0-9]/', '', $storePhone);
        if (Str::startsWith($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        } elseif (! Str::startsWith($waNumber, '62')) {
            $waNumber = '62'.$waNumber;
        }
        $waMessage = 'Halo, saya ingin menanyakan pesanan '.$order->code.' kacamata saya.';
        $waUrl = 'https://wa.me/'.$waNumber.'?text='.urlencode($waMessage);
    }
@endphp

<div class="min-h-screen antialiased bg-canvas py-8">
    <div class="max-w-4xl mx-auto px-4 space-y-6">
        @php
            $statusInfo = $statuses[$currentStatus] ?? ['label' => $currentStatus, 'desc' => ''];
        @endphp

        {{-- Header --}}
        <div class="card p-6 sm:p-8 bg-surface border border-line shadow-card rounded-2xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-bold text-text-main">Pesanan {{ $order->code }}</h1>
                    <p class="text-sm text-text-secondary">
                        Status: <span class="font-semibold text-text-main">{{ $statusInfo['label'] }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                        @if($isCompleted) bg-emerald-bg text-emerald-dark border border-emerald-soft
                        @elseif($isCanceled) bg-red-50 text-red-700 border border-red-200
                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                        @if($isCompleted)
                            <x-icon name="check" class="w-3.5 h-3.5" /> Selesai
                        @elseif($isCanceled)
                            <x-icon name="x" class="w-3.5 h-3.5" /> Dibatalkan
                        @else
                            <span class="inline-block w-2 h-2 rounded-full bg-current animate-pulse"></span>
                            Dalam Proses
                        @endif
                    </span>
                </div>
            </div>
        </div>
