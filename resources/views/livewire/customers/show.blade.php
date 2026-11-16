<div>
    <x-page-header title="{{ $customer->name }}" description="Detail pelanggan, resep, dan transaksi">
        <div class="flex items-center gap-2">
            <a href="{{ route('customers.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Kembali</a>
            <a href="{{ route('customers.edit', $customer->id) }}" wire:navigate class="btn-primary px-4 py-2 text-sm">Ubah Data</a>
            <a href="{{ route('prescriptions.create', $customer->id) }}" wire:navigate class="btn-primary px-4 py-2 text-sm">+ Resep Baru</a>
        </div>
    </x-page-header>

    <!-- Info Pelanggan -->
    <div class="card p-5">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-accent text-white flex items-center justify-center text-lg font-bold">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <div class="text-lg font-semibold text-text-main">{{ $customer->name }}</div>
                <div class="text-[11px] text-text-secondary flex items-center gap-1">
                    <span>📞</span> {{ $customer->phone ?? '-' }}
                </div>
            </div>
        </div>
        @if ($customer->address || $customer->birth_date)
            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                @if ($customer->address)
                    <div class="flex items-center gap-2 text-text-secondary">
                        <span>📍</span> {{ $customer->address }}
                    </div>
                @endif
                @if ($customer->birth_date)
                    <div class="flex items-center gap-2 text-text-secondary">
                        <span>🎂</span> {{ \Carbon\Carbon::parse($customer->birth_date)->format('d M Y') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Riwayat Resep -->
    <div class="card p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-text-main">Riwayat Resep</h2>
            @if ($customer->prescriptions->count())
                <a href="#" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua</a>
            @endif
        </div>

        @forelse ($customer->prescriptions as $r)
            <div class="rounded-lg border border-line p-4 text-sm mb-3">
                <div class="font-bold text-text-main">{{ $r->checked_at?->format('d M Y') ?? $r->created_at->format('d M Y') }}</div>
                <div class="grid sm:grid-cols-2 gap-2 mt-2">
                    <div class="bg-[#EFF6FF]/50 rounded-lg p-3 border border-[#EFF6FF]">
                        <div class="text-[10px] text-accent font-semibold uppercase">OD (Kanan)</div>
                        <div class="text-text-main">SPH {{ $r->od_sph ?? '-' }} • CYL {{ $r->od_cyl ?? '-' }} • AXIS {{ $r->od_axis ?? '-' }} • ADD {{ $r->od_add ?? '-' }}</div>
                    </div>
                    <div class="bg-[#EFF6FF]/50 rounded-lg p-3 border border-[#EFF6FF]">
                        <div class="text-[10px] text-accent font-semibold uppercase">OS (Kiri)</div>
                        <div class="text-text-main">SPH {{ $r->os_sph ?? '-' }} • CYL {{ $r->os_cyl ?? '-' }} • AXIS {{ $r->os_axis ?? '-' }} • ADD {{ $r->os_add ?? '-' }}</div>
                    </div>
                </div>
                <div class="text-[11px] text-text-secondary mt-2 flex items-center justify-between">
                    <span>PD {{ $r->pd ?? '-' }} • {{ $r->notes ?? '-' }}</span>
                    <a href="{{ route('print.prescription', $r->id) }}" target="_blank" class="text-accent font-bold">Cetak</a>
                </div>
            </div>
        @empty
            <p class="text-sm text-text-secondary text-center py-4">Belum ada resep untuk pelanggan ini.</p>
        @endforelse
    </div>

    <!-- Transaksi Terakhir -->
    <div class="card p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-text-main">Transaksi Terakhir</h2>
            <a href="{{ route('sales.index') }}" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua</a>
        </div>

        @forelse ($customer->sales as $s)
            <div class="flex justify-between text-sm py-2 border-b border-line last:border-0">
                <span class="text-text-main">{{ $s->code }}</span>
                <span class="text-right font-semibold text-text-main">Rp {{ number_format($s->total, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="text-sm text-text-secondary text-center py-2">Belum ada transaksi.</p>
        @endforelse
    </div>
</div>


