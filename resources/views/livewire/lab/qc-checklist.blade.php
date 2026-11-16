<div class="space-y-6">
    <x-page-header title="Pemeriksaan Kualitas (QC Checklist) Lab"
                   description="Pemeriksaan fisik kacamata setelah selesai diproses laboratorium sebelum diserahkan ke pelanggan" />

    <div class="grid xl:grid-cols-12 gap-6 items-start">
        <!-- Kolom Kiri: Daftar Lab Order -->
        <div class="xl:col-span-7 space-y-4">
            <div class="card p-5 bg-surface border border-line shadow-card rounded-2xl space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-sm text-text-main">Pilih Pesanan Lab</h2>
                    <span class="text-xs text-text-secondary">Siap / Diterima dari Lab</span>
                </div>

                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari nomor pesanan atau nama lab..."
                       class="input">

                <div class="flex gap-2 pt-1">
                    <button wire:click="$set('statusFilter', 'all')"
                            class="flex-1 py-1.5 px-3 rounded-xl text-xs font-semibold border transition {{ $statusFilter === 'all' ? 'bg-emerald text-white border-emerald shadow-xs' : 'bg-surface text-text-secondary border-line hover:bg-canvas' }}">
                        Semua Order
                    </button>
                    <button wire:click="$set('statusFilter', 'diterima')"
                            class="flex-1 py-1.5 px-3 rounded-xl text-xs font-semibold border transition {{ $statusFilter === 'diterima' ? 'bg-emerald text-white border-emerald shadow-xs' : 'bg-surface text-text-secondary border-line hover:bg-canvas' }}">
                        Diterima dari Lab
                    </button>
                </div>
            </div>

            <div class="card p-5 bg-surface border border-line shadow-card rounded-2xl space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-line">
                    <h3 class="font-semibold text-xs text-text-secondary uppercase tracking-wider">Antrean Pemeriksaan QC</h3>
                    <span class="text-[11px] text-emerald font-semibold">{{ $labOrders->total() }} pesanan</span>
                </div>

                <div class="space-y-2">
                    @forelse ($labOrders as $lo)
                        <div wire:click="selectOrder({{ $lo->id }})"
                             class="flex items-center justify-between gap-3 p-3.5 rounded-xl border transition cursor-pointer {{ $labOrderId === $lo->id ? 'border-emerald bg-emerald-bg/40 shadow-xs' : 'border-line bg-surface hover:bg-canvas' }}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-text-main">{{ $lo->order->order_number ?? $lo->order->code ?? '-' }}</span>
                                    <span class="status-badge info">{{ ucfirst(strtolower($lo->status)) }}</span>
                                </div>
                                <div class="text-xs text-text-secondary truncate mt-1">
                                    {{ optional($lo->order->customer)->name ?? 'Pelanggan Umum' }} &middot;
                                    <span class="font-medium text-text-main">{{ $lo->lab_supplier_name }}</span>
                                </div>
                            </div>
                            <div class="text-right text-xs">
                                <div class="text-text-secondary">{{ $lo->expected_completion_date ? \Carbon\Carbon::parse($lo->expected_completion_date)->format('d M Y') : '-' }}</div>
                                <div class="font-semibold text-text-main mt-0.5">Rp {{ number_format($lo->lab_cost, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-text-secondary text-sm">
                            <x-icon name="check" class="w-8 h-8 mx-auto text-emerald mb-2" />
                            Belum ada lab order yang perlu di-QC saat ini.
                        </div>
                    @endforelse
                </div>

                <div class="pt-2">
                    {{ $labOrders->links() }}
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form QC Checklist -->
        <div class="xl:col-span-5 card p-6 bg-surface border border-line shadow-card rounded-2xl sticky top-20 space-y-5">
            @if ($labOrderId && $selectedOrder)
                <div class="flex items-center gap-3 pb-3 border-b border-line">
                    <div class="w-10 h-10 rounded-xl bg-emerald-bg text-emerald flex items-center justify-center border border-emerald-soft flex-shrink-0">
                        <x-icon name="flask" class="w-5 h-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="font-bold text-sm text-text-main truncate">QC Pesanan {{ $selectedOrder->order->order_number ?? $selectedOrder->order->code }}</h2>
                        <p class="text-xs text-text-secondary truncate mt-0.5">Lab: {{ $selectedOrder->lab_supplier_name }}</p>
                    </div>
                </div>

                <!-- Checklist Items -->
                <div class="space-y-2">
                    <label class="label">Poin Pemeriksaan Fisik:</label>
                    @foreach ($qcItems as $key => $label)
                        <label class="flex items-center gap-3 p-2.5 rounded-xl border border-line bg-canvas hover:border-emerald-soft transition cursor-pointer">
                            <input type="checkbox"
                                   wire:model="qcItems.{{ $key }}"
                                   class="w-4 h-4 rounded text-emerald accent-emerald cursor-pointer">
                            <span class="text-xs font-medium text-text-main">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Hasil QC Radio -->
                <div>
                    <label class="label">Keputusan Hasil Pemeriksaan <span class="text-error">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border cursor-pointer transition {{ $qcResult === 'pass' ? 'border-emerald bg-emerald-bg text-emerald-dark font-bold shadow-xs' : 'border-line bg-surface text-text-secondary hover:bg-canvas' }}">
                            <input type="radio" wire:model="qcResult" value="pass" class="accent-emerald">
                            <span class="text-xs">Lulus QC (Siap Diambil)</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border cursor-pointer transition {{ $qcResult === 'fail' ? 'border-red-500 bg-red-50 text-red-700 font-bold shadow-xs' : 'border-line bg-surface text-text-secondary hover:bg-canvas' }}">
                            <input type="radio" wire:model="qcResult" value="fail" class="accent-red-600">
                            <span class="text-xs">Gagal QC (Retur ke Lab)</span>
                        </label>
                    </div>
                </div>

                <!-- Catatan QC -->
                <div>
                    <label class="label">Catatan Pemeriksa / Keterangan</label>
                    <textarea wire:model="qcNotes"
                              rows="3"
                              placeholder="Contoh: Titik fokus pas, bevel rapi, lensa bening tanpa goresan..."
                              class="input"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2 pt-2 border-t border-line">
                    <button wire:click="submitQc"
                            wire:loading.attr="disabled"
                            class="btn-primary w-full py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                        <x-icon name="check" class="w-4 h-4" />
                        <span wire:loading.remove wire:target="submitQc">Simpan Hasil Pemeriksaan</span>
                        <span wire:loading wire:target="submitQc">Menyimpan...</span>
                    </button>
                    <button wire:click="cancelQc"
                            class="btn-ghost w-full py-2 text-xs font-medium">
                        Batal
                    </button>
                </div>
            @else
                <div class="py-16 text-center text-text-secondary space-y-2">
                    <x-icon name="flask" class="w-10 h-10 mx-auto text-emerald/40" />
                    <p class="text-sm font-semibold text-text-main">Pilih Pesanan untuk QC</p>
                    <p class="text-xs max-w-xs mx-auto">Klik salah satu lab order di daftar sebelah kiri untuk memulai pemeriksaan kualitas lensa &amp; frame.</p>
                </div>
            @endif
        </div>
    </div>
</div>