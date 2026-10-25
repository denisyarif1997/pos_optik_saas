<div class="grid xl:grid-cols-5 gap-4">
    <!-- Left: Produk & Keranjang -->
    <div class="xl:col-span-3 space-y-4">

        <!-- Mode & Pencarian -->
        <div class="card p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">
                    {{ $mode === 'sale' ? 'Jual Langsung' : 'Pesanan Optik' }}
                </span>
                <button wire:click="mode = mode === 'sale' ? 'optical' : 'sale'"
                    class="btn-ghost text-xs px-3 py-1.5">Ubah Mode</button>
            </div>

            <label class="label block mb-1.5">Cari produk / scan barcode</label>
            <input type="text" wire:model.live="search"
                placeholder="Nama produk, SKU, atau barcode..."
                class="input">

            @if ($results->count())
                <div class="mt-3 space-y-2">
                    @foreach ($results as $p)
                        <button wire:click="addToCart({{ $p->id }})"
                            class="w-full flex items-center gap-3 text-left rounded-lg border border-line px-3 py-2.5 hover:bg-accent/5 transition border-accent/20">
                            <span class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-text-main truncate">{{ $p->name }}</div>
                                <div class="text-[11px] text-text-secondary">
                                    Stok {{ $p->stock }}
                                    @if ($p->stock <= $p->min_stock)
                                        <span class="status-badge warning ml-1">Menipis</span>
                                    @elseif ($p->stock === 0)
                                        <span class="status-badge error ml-1">Habis</span>
                                    @endif
                                    • Rp {{ number_format($p->sell_price, 0, ',', '.') }}
                                </div>
                            </span>
                            <span class="text-accent font-semibold text-lg">+</span>
                        </button>
                    @endforeach
                </div>
            @elseif ($search)
                <div class="mt-3 p-3 rounded-lg bg-amber-50 border border-amber-100 text-sm text-amber-700">
                    Produk tidak ditemukan untuk "{{ $search }}".
                </div>
            @endif
        </div>
    </div>

    <!-- Keranjang Belanja -->
    <div class="card p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-text-main">Keranjang ({{
                count($cartDetailed)
            }})</h3>
            @if (count($cartDetailed))
                <a href="#" wire:click="cart = []" class="text-[11px] text-text-secondary hover:text-error">Kosongkan</a>
            @endif
        </div>

        @if (count($cartDetailed))
            <div class="space-y-2 max-h-[400px] overflow-y-auto pr-2 mb-3">
                @foreach ($cartDetailed as $row)
                    <div class="flex items-center gap-3 text-sm border-b border-line pb-2 last:border-0">
                        <span class="flex-1 min-w-0">
                            <div class="font-semibold text-text-main truncate">{{ $row['product']->name }}</div>
                            <div class="text-[11px] text-text-secondary">
                                {{ $row['qty'] }} × Rp {{ number_format($row['product']->sell_price, 0, ',', '.') }}
                                @if ($row['discount'] > 0)
                                    <span class="text-error">-Rp {{ number_format($row['discount'], 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </span>
                        <div class="text-right text-text-main font-medium">
                            Rp {{ number_format((($row['product']->sell_price * $row['qty']) - $row['discount']), 0, ',', '.') }}
                        </div>
                        <button wire:click="removeRow({{ $row['index'] }})"
                            class="text-text-secondary hover:text-error">×</button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state py-4">
                <p class="text-[11px] text-text-secondary">Keranjang masih kosong.</p>
            </div>
        @endif
    </div>

    <!-- Right: Ringkasan & Pembayaran -->
    <div class="xl:col-span-2 space-y-4">
        <div class="card p-4">
            <h3 class="text-sm font-semibold text-text-main mb-3">Ringkasan Pembayaran</h3>
            <table class="w-full text-sm text-text-secondary mb-4">
                <tr class="border-b border-line py-2">
                    <td>Subtotal</td>
                    <td class="text-right text-text-main">Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</td>
                </tr>
                <tr class="border-b border-line py-2">
                    <td>Diskon</td>
                    <td class="text-right text-text-main">-Rp {{ number_format($totals['discount'], 0, ',', '.') }}</td>
                </tr>
                <tr class="border-b border-line py-2">
                    <td class="font-semibold text-text-main">Total</td>
                    <td class="text-right font-semibold text-text-main">Rp {{ number_format($totals['total'], 0, ',', '.') }}</td>
                </tr>
            </table>

            <label class="label block mb-1.5">Pelanggan</label>
            <select wire:model="customer_id" class="input">
                <option value="">- Pilih pelanggan -</option>
                @foreach ($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>

            @if ($mode === 'sale')
                <label class="label block mb-1.5 mt-4">Metode Pembayaran</label>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 p-2 rounded-lg border border-line hover:bg-canvas cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="cash" class="accent"> Tunai
                    </label>
                    <label class="flex items-center gap-2 p-2 rounded-lg border border-line hover:bg-canvas cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="non_cash" class="accent"> Non Tunai
                    </label>
                </div>

                <label class="label block mb-1.5 mt-4">Jumlah Bayar</label>
                <input type="number" wire:model.live="pay_amount" placeholder="0" class="input">

                <div class="mt-2 text-[11px] text-text-secondary">
                    Kembalian: Rp {{ number_format(max(0, $pay_amount - $totals['total']), 0, ',', '.') }}
                </div>
            @endif

            @if ($mode === 'optical')
                <label class="label block mb-1.5 mt-4">Lab Supplier</label>
                <input type="text" wire:model="lab_name" placeholder="Nama lab..." class="input">
                <label class="label block mb-1.5 mt-4">Estimasi Selesai</label>
                <input type="date" wire:model="promised_date" class="input">
            @endif

            <div class="mt-4">
                <button wire:click="checkout"
                    class="btn-primary w-full py-2.5 text-sm font-semibold {{ count($cartDetailed) === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ count($cartDetailed) === 0 ? 'disabled' : '' }}>
                    {{ $mode === 'sale' ? 'Bayar & Selesai' : 'Buat Pesanan Optik' }}
                </button>
            </div>
        </div>
    </div>
</div>