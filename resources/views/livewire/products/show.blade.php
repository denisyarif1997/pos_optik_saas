<div>
    <x-page-header title="Detail Produk" description="Informasi lengkap produk beserta stok dan harga">
        <div class="flex justify-end">
            <a href="{{ route('products.index') }}" wire:navigate class="btn-ghost px-4 py-2 text-sm">Kembali ke Daftar</a>
        </div>
    </x-page-header>

    <div class="grid max-w-4xl mx-auto gap-6 py-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="label text-sm font-medium mb-2">Nama Produk</label>
                    <p class="text-lg font-semibold text-text-main">{{ $product->name }}</p>
                </div>
                <div>
                    <label class="label text-sm font-medium mb-2">Tipe</label>
                    <p class="text-lg font-medium">
                        @if($product->type === 'frame')
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">Frame</span>
                        @elseif($product->type === 'lensa')
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">Lensa</span>
                        @elseif($product->type === 'softlens')
                            <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded">Softlens</span>
                        @elseif($product->type === 'cairan')
                            <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded">Cairan</span>
                        @elseif($product->type === 'aksesori')
                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2 py-1 rounded">Aksesori</span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="label text-sm font-medium mb-2">SKU</label>
                    <p class="text-lg font-medium">{{ $product->sku ?? '—' }}</p>
                </div>
                <div>
                    <label class="label text-sm font-medium mb-2">Barcode</label>
                    <p class="text-lg font-medium">{{ $product->barcode ?? '—' }}</p>
                </div>

                <div>
                    <label class="label text-sm font-medium mb-2">Harga Jual</label>
                    <p class="text-2xl font-bold text-primary">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="label text-sm font-medium mb-2">Harga Pokok</label>
                    <p class="text-lg font-medium text-slate-500">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-line">
                <label class="label text-sm font-medium mb-2">Stok</label>
                <div class="flex items-center gap-4">
                    <span class="text-[11px] font-medium text-text-secondary">Stok Awal:</span>
                    <span class="text-3xl font-bold {{ $product->stock <= $product->min_stock ? 'text-error' : 'text-success' }}">{{ $product->stock }}</span>

                    <span class="text-[11px] font-medium text-text-secondary">Minimal:</span>
                    <span class="text-[11px] font-bold {{ $product->stock <= $product->min_stock ? 'text-error' : 'text-success' }}">{{ $product->min_stock }}</span>
                </div>

                @if($product->stock <= $product->min_stock)
                    <p class="text-[10px] text-error mt-2">⚠ Stok meledak minimum!</p>
                @endif
            </div>
        </div>

        @if($product->transactions->isNotEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="font-semibold mb-4">Transaksi Terakhir</h2>
                <div class="space-y-3">
                    @foreach($product->transactions as $t)
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-100">
                            <div class="font-medium">{{ Str::limit($t->reference_number, 20) }}</div>
                            <div class="text-[11px] text-slate-500">
                                {{ $t->created_at->diffForHum() }} ago
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <x-empty-state title="Belum ada transaksi"
                description="Produk ini belum pernah terjual. Transaksi akan muncul setelah pemasokan atau penjualan."
                />
        @endif
    </div>
</div>