<div class="space-y-6">
    @php
        $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    @endphp

    <x-page-header :title="$customer->name" description="Detail pelanggan, riwayat resep, dan transaksi">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('customers.index') }}" wire:navigate class="btn-ghost px-3.5 py-2 text-sm">
                    Kembali
                </a>
                <a href="{{ route('customers.edit', $customer->id) }}" wire:navigate class="btn-ghost border border-line px-3.5 py-2 text-sm">
                    Ubah Data
                </a>
                <a href="{{ route('prescriptions.create', $customer->id) }}" wire:navigate class="btn-primary px-3.5 py-2 text-sm">
                    + Resep Baru
                </a>
            </div>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Kolom Kiri: Profil & Transaksi --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Info Pelanggan --}}
            <div class="card p-5">
                <div class="flex items-center gap-3.5 pb-4 border-b border-line">
                    <div class="w-11 h-11 rounded-lg bg-slate-900 text-white flex items-center justify-center text-base font-semibold shrink-0">
                        {{ strtoupper(mb_substr($customer->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-base font-semibold text-text-main truncate">{{ $customer->name }}</h2>
                        <p class="text-xs text-text-secondary tabular-nums mt-0.5 truncate">
                            {{ $customer->phone ?: 'No. telepon belum tercatat' }}
                        </p>
                    </div>
                </div>

                <dl class="mt-4 space-y-3 text-xs">
                    <div>
                        <dt class="text-text-secondary">Tanggal Lahir</dt>
                        <dd class="text-sm font-medium text-text-main mt-0.5 tabular-nums">
                            {{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->translatedFormat('d M Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-text-secondary">Alamat</dt>
                        <dd class="text-sm text-text-main mt-0.5 leading-relaxed">
                            {{ $customer->address ?: '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Transaksi Terakhir --}}
            <div class="card p-5">
                <div class="flex items-center justify-between pb-3 border-b border-line">
                    <h2 class="text-sm font-semibold text-text-main">Transaksi Terakhir</h2>
                    <a href="{{ route('sales.index') }}" wire:navigate class="text-xs font-medium text-accent hover:text-accent-hover">
                        Semua
                    </a>
                </div>

                <div class="divide-y divide-line">
                    @forelse ($customer->sales as $s)
                        <div class="flex items-center justify-between gap-3 py-2.5 first:pt-3 last:pb-0 text-sm">
                            <div class="min-w-0">
                                <a href="{{ route('sales.show', $s->id) }}" wire:navigate
                                   class="font-medium text-text-main hover:text-accent truncate block">
                                    {{ $s->code }}
                                </a>
                                @if ($s->created_at)
                                    <span class="text-[11px] text-text-secondary tabular-nums block">
                                        {{ $s->created_at->translatedFormat('d M Y') }}
                                    </span>
                                @endif
                            </div>
                            <span class="font-semibold text-text-main tabular-nums whitespace-nowrap">
                                {{ $rp($s->total) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-text-secondary py-6 text-center">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Riwayat Resep --}}
        <div class="lg:col-span-8">
            <div class="card p-5">
                <div class="flex items-center justify-between gap-4 pb-4 border-b border-line">
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Riwayat Resep</h2>
                        <p class="text-xs text-text-secondary mt-0.5">Catatan ukuran lensa berdasarkan pemeriksaan</p>
                    </div>
                    @if ($customer->prescriptions->count() > 0)
                        <span class="text-xs text-text-secondary tabular-nums whitespace-nowrap">
                            {{ $customer->prescriptions->count() }} resep
                        </span>
                    @endif
                </div>

                <div class="divide-y divide-line">
                    @forelse ($customer->prescriptions as $r)
                        @php
                            $prescriptionDate = ($r->checked_at ?? $r->created_at)?->translatedFormat('d M Y') ?? '-';
                        @endphp

                        <article class="py-4 first:pt-4 last:pb-0">
                            <div class="flex items-center justify-between gap-4 mb-3">
                                <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                    <span class="text-sm font-semibold text-text-main tabular-nums">
                                        {{ $prescriptionDate }}
                                    </span>
                                    <span class="text-xs text-text-secondary tabular-nums">
                                        PD: <strong class="font-medium text-text-main">{{ $r->pd ?? '-' }}</strong>
                                    </span>
                                </div>
                                <a href="{{ route('print.prescription', $r->id) }}" target="_blank"
                                   class="text-xs font-medium text-accent hover:text-accent-hover underline underline-offset-2 whitespace-nowrap">
                                    Cetak Resep
                                </a>
                            </div>

                            <div class="overflow-x-auto border border-line rounded-lg">
                                <table class="w-full text-xs border-collapse tabular-nums">
                                    <thead class="bg-slate-50 text-text-secondary border-b border-line">
                                        <tr>
                                            <th class="py-2 px-3 text-left font-medium w-28">Mata</th>
                                            <th class="py-2 px-3 text-right font-medium">SPH</th>
                                            <th class="py-2 px-3 text-right font-medium">CYL</th>
                                            <th class="py-2 px-3 text-right font-medium">AXIS</th>
                                            <th class="py-2 px-3 text-right font-medium">ADD</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-line text-text-main">
                                        <tr>
                                            <td class="py-2 px-3 font-medium whitespace-nowrap">OD (Kanan)</td>
                                            <td class="py-2 px-3 text-right">{{ $r->od_sph ?? '-' }}</td>
                                            <td class="py-2 px-3 text-right">{{ $r->od_cyl ?? '-' }}</td>
                                            <td class="py-2 px-3 text-right">{{ $r->od_axis ?? '-' }}</td>
                                            <td class="py-2 px-3 text-right">{{ $r->od_add ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-3 font-medium whitespace-nowrap">OS (Kiri)</td>
                                            <td class="py-2 px-3 text-right">{{ $r->os_sph ?? '-' }}</td>
                                            <td class="py-2 px-3 text-right">{{ $r->os_cyl ?? '-' }}</td>
                                            <td class="py-2 px-3 text-right">{{ $r->os_axis ?? '-' }}</td>
                                            <td class="py-2 px-3 text-right">{{ $r->os_add ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if ($r->notes)
                                <p class="text-xs text-text-secondary mt-2.5 leading-relaxed">
                                    <span class="font-medium text-text-main">Catatan:</span> {{ $r->notes }}
                                </p>
                            @endif
                        </article>
                    @empty
                        <div class="py-10 text-center">
                            <p class="text-sm text-text-secondary">Belum ada resep untuk pelanggan ini.</p>
                            <a href="{{ route('prescriptions.create', $customer->id) }}" wire:navigate
                               class="inline-block mt-3 text-xs font-medium text-accent hover:text-accent-hover underline underline-offset-2">
                                Buat resep pertama
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>