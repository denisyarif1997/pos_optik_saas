<!-- Wrapper Utama dengan Alpine.js untuk perhitungan Frontend -->
<div 
    class="flex flex-col lg:flex-row gap-6 items-start -m-2 sm:-m-4"
    x-on:cashier-total-updated="total = Number($event.detail.total)"
    x-data="{
        payAmount: @entangle('pay_amount'),
        total: {{ (int) $totals['total'] }},
        get change() {
            return Math.max(0, this.payAmount - this.total);
        },
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }
    }"
>
    
    <!-- KIRI: Pencarian & Produk -->
    <div class="flex-1 w-full flex flex-col lg:h-[calc(100vh-80px)]">
        
        <!-- Search Bar Besar dengan Pop-Up Dropdown -->
        <div class="relative z-50" x-data="{ open: true }">
            <div class="soft-card p-4 flex gap-4 items-center relative z-10 bg-white">
                <div class="absolute inset-y-0 left-0 pl-8 flex items-center pointer-events-none">
                    <x-icon name="search" class="w-6 h-6 text-text-muted" />
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    @focus="open = true"
                    @click.away="open = false"
                    class="input pl-14 py-4 text-lg w-full font-medium border-transparent shadow-none focus:ring-0 focus:border-transparent bg-canvas/50 focus:bg-white transition-colors rounded-xl" 
                    placeholder="Ketik nama produk, SKU, atau scan Barcode disini..."
                    autofocus
                >
            </div>

            <!-- POP UP Hasil Pencarian -->
            @if(strlen(trim($search)) > 0)
                <div x-show="open" x-transition class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-line overflow-hidden max-h-[450px] overflow-y-auto">
                    @if ($results->count())
                        <div class="p-2 space-y-1">
                            @foreach ($results as $p)
                                <button wire:click="addToCart({{ $p->id }})" wire:key="search-res-{{ $p->id }}" class="w-full flex items-center justify-between text-left p-3.5 rounded-lg hover:bg-emerald-bg hover:text-emerald-dark transition-colors border border-transparent hover:border-emerald-soft group cursor-pointer">
                                    <div>
                                        <div class="font-bold text-[15px] group-hover:text-emerald text-text-main">{{ $p->name }}</div>
                                        <div class="text-[12px] text-text-secondary mt-1">
                                            SKU: <span class="font-mono bg-canvas px-1 py-0.5 rounded text-text-main">{{ $p->sku ?? $p->barcode ?? '-' }}</span> 
                                            &nbsp;&bull;&nbsp; Stok: <span class="{{ $p->stock <= 0 ? 'text-error font-bold' : 'font-medium' }}">{{ $p->stock }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-text-main group-hover:text-emerald text-lg">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-text-muted group-hover:text-emerald opacity-0 group-hover:opacity-100 transition-opacity">Ketuk untuk tambah +</div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center text-text-muted bg-canvas/30">
                            <x-icon name="box" class="w-10 h-10 mx-auto mb-3 opacity-40" />
                            <p class="font-medium text-sm">Produk dengan kata kunci "<span class="text-text-main font-bold">{{ $search }}</span>" tidak ditemukan.</p>
                            <p class="text-[11px] mt-1">Periksa kembali ejaan atau coba scan ulang barcode.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Area Latar Belakang Kiri -->
        <div class="flex-1 overflow-y-auto pb-6 py-10 flex flex-col items-center justify-center text-text-muted/60 relative z-0">
            <div class="w-24 h-24 mb-6 rounded-3xl bg-emerald-bg text-emerald flex items-center justify-center shadow-sm border border-emerald-soft">
                <x-icon name="shopping-bag" class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-bold text-text-secondary mb-2">Kasir Siap Digunakan</h3>
            <p class="text-sm max-w-sm text-center">Gunakan alat scanner Barcode atau ketik manual nama produk di kolom pencarian atas untuk memulai transaksi.</p>
        </div>
    </div>


    <!-- KANAN: Keranjang & Pembayaran -->
    <div class="w-full lg:w-[420px] soft-card flex flex-col lg:h-[calc(100vh-80px)] lg:min-h-[560px] lg:sticky lg:top-4 shadow-xl shrink-0 overflow-hidden">

        <!-- Toggle Mode Penjualan -->
        <div class="shrink-0 p-3 bg-canvas border-b border-line flex gap-2">
            <button wire:click="$set('mode', 'sale')" class="flex-1 py-2.5 rounded-lg font-semibold text-sm transition-all {{ $mode === 'sale' ? 'bg-emerald text-white shadow-md' : 'bg-surface text-text-secondary hover:bg-emerald-soft' }}">
                Jual Langsung
            </button>
            <button wire:click="$set('mode', 'optical')" class="flex-1 py-2.5 rounded-lg font-semibold text-sm transition-all {{ $mode === 'optical' ? 'bg-emerald text-white shadow-md' : 'bg-surface text-text-secondary hover:bg-emerald-soft' }}">
                Pesanan Optik
            </button>
        </div>

        <!-- KERANJANG: fleksibel, min 180px, scroll sendiri -->
        <div class="flex-1 min-h-[180px] max-lg:max-h-[60vh] overflow-y-auto p-4 bg-canvas/30">
            @if (count($cartDetailed))
                <div class="space-y-3">
                    @foreach ($cartDetailed as $row)
                        @php $idx = $row['index']; @endphp
                        <div wire:key="cart-row-{{ $idx }}" class="bg-white border border-line rounded-xl p-3 shadow-sm flex flex-col gap-2">
                            <div class="flex justify-between items-start gap-2">
                                <h5 class="font-semibold text-sm text-text-main leading-tight">{{ $row['product']->name }}</h5>
                                <button wire:click="removeRow({{ $idx }})" class="text-text-muted hover:text-error p-1 -mt-1 -mr-1 transition-colors">
                                    <x-icon name="x" class="w-4 h-4" />
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-xs font-medium text-text-secondary">
                                    Rp {{ number_format($row['product']->sell_price, 0, ',', '.') }}
                                </div>

                                <!-- QTY Controller -->
                                <div class="flex items-center border border-line rounded-lg overflow-hidden bg-canvas">
                                    <button wire:click="decrementQty({{ $idx }})" class="px-2.5 py-1 text-text-main hover:bg-white transition-colors">-</button>
                                    <div class="px-3 py-1 font-semibold text-sm bg-white border-x border-line">{{ $row['qty'] }}</div>
                                    <button wire:click="incrementQty({{ $idx }})" class="px-2.5 py-1 text-text-main hover:bg-white transition-colors">+</button>
                                </div>
                            </div>

                            @if (in_array($row['product']->type, ['STOCK_LENS', 'RX_LENS', 'CONTACT_LENS']))
                                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-line">
                                    @foreach (['sph' => 'SPH', 'cyl' => 'CYL', 'add_val' => 'ADD'] as $field => $label)
                                        <div class="min-w-0">
                                            <label class="block text-[10px] text-text-secondary mb-1">{{ $label }}</label>
                                            <input type="number" step="0.25" inputmode="decimal"
                                                wire:model.blur="cart.{{ $idx }}.{{ $field }}"
                                                placeholder="{{ $label }}"
                                                class="input w-full text-[11px] py-1.5 px-1 text-center @error("cart.$idx.$field") border-error @enderror"/>
                                            @error("cart.$idx.$field")
                                                <p class="mt-1 text-[10px] text-error leading-tight">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center text-text-muted opacity-60">
                    <x-icon name="shopping-cart" class="w-12 h-12 mb-3" />
                    <p class="text-sm">Keranjang kosong</p>
                </div>
            @endif
        </div>

        <!-- FORM PEMBAYARAN: dibatasi 45% tinggi panel, scroll sendiri -->
        <div class="shrink min-h-[140px] lg:max-h-[45%] overflow-y-auto p-4 border-t border-line bg-white">

            <div class="mb-3">
                @if ($mode === 'optical')
                    <label for="customerId" class="label text-[10px]">Pelanggan <span class="text-error">*</span></label>
                @endif
                <select id="customerId" wire:model="customer_id" class="input py-2.5 bg-canvas font-medium text-sm w-full" {{ $mode === 'optical' ? 'required' : '' }}>
                    <option value="">{{ $mode === 'optical' ? '-- Pilih Pelanggan (Wajib) --' : '-- Pilih Pelanggan (Opsional) --' }}</option>
                    @foreach ($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                    @endforeach
                </select>
                @error('customer_id') <p class="mt-1 text-[10px] text-error">{{ $message }}</p> @enderror
            </div>

            <div class="mb-3 grid grid-cols-2 gap-3">
                <div>
                    <label for="discountPercent" class="label text-[10px]">Diskon transaksi</label>
                    <div class="relative">
                        <input
                            id="discountPercent"
                            type="number"
                            min="0"
                            max="100"
                            step="0.01"
                            wire:model.live.debounce.300ms="discount_percent"
                            class="input py-2 pr-8 text-sm"
                            placeholder="0"
                        >
                        <span class="absolute inset-y-0 right-3 flex items-center text-xs text-text-muted">%</span>
                    </div>
                    @error('discount_percent') <p class="mt-1 text-[10px] text-error">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-2 rounded-xl border border-line bg-canvas px-3 py-2.5 text-xs font-semibold text-text-main">
                    <input type="checkbox" wire:model.live="apply_ppn" class="h-4 w-4 rounded border-line text-emerald focus:ring-emerald/20">
                    <span>
                        <span class="block">PPN 11%</span>
                        <span class="block text-[10px] font-normal text-text-secondary">Opsional</span>
                    </span>
                </label>
            </div>

            <!-- Pesanan Optik Fields -->
            @if ($mode === 'optical')
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="label text-[10px]">Lab Supplier</label>
                        <input type="text" wire:model="lab_name" class="input py-2 text-sm" placeholder="Nama Lab">
                    </div>
                    <div>
                        <label class="label text-[10px]">Estimasi Selesai</label>
                        <input type="date" wire:model="promised_date" class="input py-2 text-sm">
                    </div>
                </div>
            @endif

            <!-- Metode Pembayaran -->
            <div class="mb-3">
                <p class="label text-[10px]">{{ $mode === 'sale' ? 'Metode pembayaran' : 'Metode pembayaran DP' }}</p>
                <div class="grid grid-cols-4 gap-2">
                    @foreach (['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'card' => 'Kartu'] as $val => $lbl)
                        <label class="flex items-center justify-center p-2 rounded-xl border border-line bg-canvas hover:border-emerald cursor-pointer transition-colors has-[:checked]:bg-emerald-bg has-[:checked]:border-emerald has-[:checked]:text-emerald-dark">
                            <input type="radio" wire:model="payment_method" value="{{ $val }}" class="sr-only">
                            <span class="font-semibold text-xs">{{ $lbl }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="cashAccountId" class="label">Masuk ke kas</label>
                <select id="cashAccountId" wire:model="cash_account_id" class="input py-2.5 text-sm">
                    <option value="">Pilih akun kas</option>
                    @foreach ($cashAccounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->type === 'cash' ? 'Tunai' : 'Bank' }})</option>
                    @endforeach
                </select>
                @error('cash_account_id') <p class="mt-1 text-[10px] text-error">{{ $message }}</p> @enderror
            </div>

            <!-- Perhitungan Pembayaran (Instant via Alpine.js) -->
            <div>
                <label for="payAmount" class="label">{{ $mode === 'sale' ? 'Jumlah uang diterima' : 'DP yang diterima (opsional)' }}</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-lg font-bold text-emerald-dark/70 pointer-events-none">Rp</span>
                    <input
                        id="payAmount"
                        type="text"
                        inputmode="numeric"
                        autocomplete="off"
                        class="input pl-12 py-3 text-lg font-bold text-emerald-dark bg-emerald-bg/50 focus:bg-white text-right tabular-nums"
                        placeholder="0"
                        :value="payAmount ? new Intl.NumberFormat('id-ID').format(payAmount) : ''"
                        @input="
                            payAmount = parseInt($event.target.value.replace(/\D/g, '')) || 0;
                            $event.target.value = payAmount ? new Intl.NumberFormat('id-ID').format(payAmount) : '';
                        "
                        @focus="$event.target.select()"
                    >
                </div>

                <div class="mt-2 flex flex-wrap gap-1.5">
                    <button type="button" class="btn-ghost !px-3 !py-1.5 text-xs" @click="payAmount = total">Uang pas</button>
                    <template x-for="nominal in [50000, 100000, 200000]" :key="nominal">
                        <button type="button" class="btn-ghost !px-3 !py-1.5 text-xs"
                                @click="payAmount = nominal"
                                x-text="new Intl.NumberFormat('id-ID').format(nominal)"></button>
                    </template>
                    <button type="button" class="btn-ghost !px-3 !py-1.5 text-xs ml-auto" @click="payAmount = 0">Reset</button>
                </div>

                <div class="mt-3 text-sm font-medium flex justify-between px-1"
                     :class="payAmount === 0 ? 'text-text-secondary' : (payAmount < total ? 'text-error' : 'text-emerald')">
                    <span x-text="payAmount > 0 && payAmount < total ? 'Kurang:' : 'Kembalian:'"></span>
                    <span class="font-bold tabular-nums"
                          x-text="formatRupiah(payAmount > 0 && payAmount < total ? total - payAmount : change)"></span>
                </div>
            </div>
        </div>

        <!-- FOOTER TETAP: ringkasan total + tombol bayar (selalu terlihat) -->
        <div class="shrink-0 p-4 border-t border-line bg-white shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.08)]">
            <div class="mb-3 space-y-1 text-xs">
                <div class="flex justify-between text-text-secondary">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
                </div>
                @if ($totals['item_discount'] > 0)
                    <div class="flex justify-between text-text-secondary">
                        <span>Diskon item</span>
                        <span>- Rp {{ number_format($totals['item_discount'], 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($totals['transaction_discount'] > 0)
                    <div class="flex justify-between text-text-secondary">
                        <span>Diskon transaksi ({{ number_format((float) $discount_percent, 2, ',', '.') }}%)</span>
                        <span>- Rp {{ number_format($totals['transaction_discount'], 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($apply_ppn)
                    <div class="flex justify-between font-medium text-emerald-dark">
                        <span>PPN (11%)</span>
                        <span>+ Rp {{ number_format($totals['ppn_amount'], 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between mb-3 pt-2 border-t border-line">
                <div class="text-sm text-text-secondary">Total Tagihan</div>
                <div class="text-2xl font-bold text-text-main">Rp {{ number_format($totals['total'], 0, ',', '.') }}</div>
            </div>

            <button wire:click="checkout"
                class="btn-primary w-full py-3.5 text-base shadow-lg {{ count($cartDetailed) === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ count($cartDetailed) === 0 ? 'disabled' : '' }}>
                {{ $mode === 'sale' ? 'Bayar Transaksi' : 'Buat Pesanan Optik' }}
            </button>
        </div>
    </div>
</div>