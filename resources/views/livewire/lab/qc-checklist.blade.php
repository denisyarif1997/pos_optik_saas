<div class="grid xl:grid-cols-5 gap-4">
    <div class="xl:col-span-3 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-slate-700 mb-3">Daftar Lab Order untuk QC</h2>
            <input type="text" wire:model.live="search" placeholder="Cari nomor pesanan atau nama lab..." class="w-full rounded-lg bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300 mb-2">
            <div class="flex gap-2">
                <button wire:click="statusFilter = 'all'" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium border {{ $statusFilter === 'all' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">Semua</button>
                <button wire:click="statusFilter = 'diterima'" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium border {{ $statusFilter === 'diterima' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">Diterima Lab</button>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-2">
            <h2 class="font-semibold text-slate-700 mb-3">Lab Order (Diterima)</h2>
            @forelse ($labOrders as $lo)
                <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 px-3 py-2.5 hover:bg-slate-50 cursor-pointer group" wire:click="selectOrder({{ $lo->id }})">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold truncate">{{ $lo->order->order_number }}</div>
                        <div class="text-xs text-slate-500">{{ optional($lo->order->customer)->name ?? 'Umum' }} • {{ $lo->lab_supplier_name }}</div>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">{{ ucfirst(strtolower($lo->status)) }}</span>
                    </div>
                    <div class="text-right text-xs">
                        <div class="text-slate-500">{{ $lo->expected_completion_date ? \Carbon\Carbon::parse($lo->expected_completion_date)->format('d M Y') : '-' }}</div>
                        <div class="text-slate-400">Rp {{ number_format($lo->lab_cost, 0, ',', '.') }}</div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400 text-center py-6">Belum ada lab order yang diterima untuk QC.</p>
            @endforelse
            {{ $labOrders->links() }}
        </div>
    </div>
    <div class="xl:col-span-2">
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4 xl:sticky xl:top-24">
            @if ($labOrderId)
                <div>
                    <h2 class="font-semibold text-slate-700 mb-3">QC Checklist</h2>
                    <p class="text-sm text-slate-500 mb-3">Pesanan: <span class="font-bold">{{ $selectedOrder->order->order_number }}</span></p>
                    <p class="text-sm text-slate-500 mb-4">Lab: {{ $selectedOrder->lab_supplier_name }} • {{ \Carbon\Carbon::parse($selectedOrder->expected_completion_date)->format('d M Y') }}</p>

                    <div class="space-y-2">
                        @foreach ($qcItems as $key => $label)
                            <div class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 {{ ($qcItems[$key] ?? true) ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200' }}">
                                <input type="checkbox" wire:model="qcItems.{{ $key }}" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                <label class="text-sm text-slate-700 cursor-pointer">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label class="text-sm font-semibold block mb-1.5">Hasil QC</label>
                        <div class="flex gap-3">
                            <label class="flex-1 flex items-center gap-2 cursor-pointer border rounded-lg p-2 {{ $qcResult === 'pass' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white' }}">
                                <input type="radio" wire:model="qcResult" value="pass" class="w-4 h-4 text-emerald-600"> <span class="text-sm font-medium text-slate-700">Lulus QC</span>
                            </label>
                            <label class="flex-1 flex items-center gap-2 cursor-pointer border rounded-lg p-2 {{ $qcResult === 'fail' ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-white' }}">
                                <input type="radio" wire:model="qcResult" value="fail" class="w-4 h-4 text-red-600"> <span class="text-sm font-medium text-slate-700">Gagal QC</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-sm font-semibold block mb-1.5">Catatan QC</label>
                        <textarea wire:model="qcNotes" rows="3" placeholder="Catatan hasil pemeriksaan kualitas..." class="w-full rounded-lg bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm"></textarea>
                    </div>

                    <button wire:click="submitQc" @disabled($labOrderId === null) class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold py-2.5 disabled:opacity-50 disabled:cursor-not-allowed mt-4">Simpan Hasil QC</button>
                    <button wire:click="cancelQc" class="w-full text-sm text-slate-500 hover:text-slate-700 mt-2">Batal</button>
                </div>
            @else
                <div class="text-center py-10 text-slate-400">
                    <p class="text-sm">Pilih lab order dari daftar di samping untuk melakukan pemeriksaan QC.</p>
                </div>
            @endif
        </div>
    </div>
</div>