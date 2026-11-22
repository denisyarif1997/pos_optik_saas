<div class="grid xl:grid-cols-5 gap-4">
    <div class="xl:col-span-3 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-slate-700 mb-3">Daftar Lab Order</h2>
            <input type="text" wire:model.live="search" placeholder="Cari nomor pesanan atau nama lab..." class="w-full rounded-lg bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-300 mb-2">
            <div class="flex gap-2">
                @foreach ($statuses as $status => $label)
                    <button wire:click="$set('statusFilter', '{{ $status }}')" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium border {{ $statusFilter === $status ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">{{ $label }}</button>
                @endforeach
                <button wire:click="$set('statusFilter', 'all')" class="px-3 py-2 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50">Semua</button>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-2">
            <h2 class="font-semibold text-slate-700 mb-3">Lab Orders</h2>
            @forelse ($labOrders as $lo)
                <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 px-3 py-2.5 hover:bg-slate-50 cursor-pointer group" wire:click="selectOrder({{ $lo->id }})">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold truncate">{{ $lo->order?->order_number ?? 'Order #' . $lo->order_id }}</div>
                        <div class="text-xs text-slate-500">{{ $lo->order?->customer?->name ?? 'Umum' }} • {{ $lo->lab_supplier_name }}</div>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold {{ $lo->status === 'DITERIMA' ? 'bg-emerald-100 text-emerald-700' : ($lo->status === 'SALAH' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700') }}">{{ ucfirst(strtolower($lo->status)) }}</span>
                    </div>
                    <div class="text-right text-xs">
                        <div class="text-slate-500">{{ $lo->expected_completion_date ? \Carbon\Carbon::parse($lo->expected_completion_date)->format('d M Y') : '-' }}</div>
                        <div class="text-slate-400">Rp {{ number_format($lo->lab_cost, 0, ',', '.') }}</div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400 text-center py-6">Belum ada lab order.</p>
            @endforelse
            {{ $labOrders->links() }}
        </div>
    </div>

    <div class="xl:col-span-2">
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4 xl:sticky xl:top-24">
            @if ($selectedOrder)
                <div>
                    <h2 class="font-semibold text-slate-700 mb-3">Ubah Status Lab Order</h2>
                    <p class="text-sm text-slate-500 mb-3">Pesanan: <span class="font-bold">{{ $selectedOrder->order?->order_number ?? 'Order #' . $selectedOrder->order_id }}</span></p>
                    <div>
                        <label class="text-sm font-semibold block mb-1.5">Status Baru</label>
                        <select wire:model="newStatus" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                            @foreach ($statuses as $status => $label)
                                <option value="{{ $status }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label class="text-sm font-semibold block mb-1.5">Tanggal Janji (Promised Date)</label>
                        <input type="date" wire:model="promisedDate" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
                    </div>
                    @if ($newStatus === 'SALAH')
                        <div class="mt-3">
                            <label class="text-sm font-semibold block mb-1.5">Alasan Penolakan</label>
                            <textarea wire:model="rejectionReason" rows="3" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm"></textarea>
                        </div>
                    @endif
                    <button wire:click="updateStatus" @disabled(!$selectedOrder) class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold py-2.5 disabled:opacity-50 disabled:cursor-not-allowed mt-4">Simpan Perubahan</button>
                    <button wire:click="$set('orderId', null)" class="w-full text-sm text-slate-500 hover:text-slate-700 mt-2">Batal</button>
                </div>
            @else
                <div class="text-center py-10 text-slate-400">
                    <p class="text-sm">Pilih lab order dari daftar di samping untuk mengubah status.</p>
                </div>
            @endif
        </div>
    </div>
</div>