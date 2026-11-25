@php
    $isPaid = $sale->payment_status === 'lunas';
    $remaining = max(0, (float) $sale->total - (float) $sale->paid_total);
    $paidPercent = $sale->total > 0 ? min(100, round(((float) $sale->paid_total / (float) $sale->total) * 100)) : 0;

    $itemDiscountTotal = (float) $sale->items->sum('discount');
    $transactionDiscount = max(0, (float) $sale->discount_total - $itemDiscountTotal);

    $methodLabels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'card' => 'Kartu'];
@endphp

<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('sales.index') }}" wire:navigate
           class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400">
            <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/></svg>
            Kembali
        </a>

        <div class="ml-auto flex items-center gap-2">
            <a href="{{ route('print.sale', $sale->id) }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-emerald-500/20 transition hover:bg-emerald-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2">
                <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.126.153A2.212 2.212 0 0 1 18 8.653v4.097A2.25 2.25 0 0 1 15.75 15h-.241l.305 1.984A1.75 1.75 0 0 1 14.084 19H5.915a1.75 1.75 0 0 1-1.73-2.016L4.49 15H4.25A2.25 2.25 0 0 1 2 12.75V8.653c0-1.082.775-2.034 1.874-2.198.374-.056.749-.107 1.126-.153V2.75Zm1.5 0a.25.25 0 0 1 .25-.25h6.5a.25.25 0 0 1 .25.25v3.373a41.9 41.9 0 0 0-7 0V2.75Zm-.235 11.75h7.47l-.347 2.25a.25.25 0 0 1-.247.213H6.859a.25.25 0 0 1-.247-.213L6.265 14.5Z" clip-rule="evenodd"/></svg>
                Cetak nota
            </a>
        </div>
    </div>

    <div class="grid items-start gap-5 xl:grid-cols-3">

        {{-- Kolom kiri: nota --}}
        <section class="xl:col-span-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-wrap items-start gap-4 border-b border-slate-100 p-6">
                <div class="min-w-0">
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Nota {{ $sale->code }}</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $sale->sold_at->format('d M Y, H:i') }}
                        <span class="mx-1 text-slate-300">|</span>
                        {{ $sale->customer?->name ?? 'Pelanggan umum' }}
                    </p>
                </div>

                <span class="ml-auto inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $isPaid ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    <span class="size-1.5 rounded-full {{ $isPaid ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ $isPaid ? 'Lunas' : ucfirst($sale->payment_status) }}
                </span>
            </div>

            {{-- Daftar item --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-left text-xs font-medium text-slate-500">
                            <th class="px-6 py-3 font-medium">Produk</th>
                            <th class="px-3 py-3 text-center font-medium">Qty</th>
                            <th class="px-6 py-3 text-right font-medium">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($sale->items as $it)
                            @php
                                $returnedQty = (int) ($returnsByItem[$it->product_id]['qty'] ?? 0);
                                $remainingQty = (int) $it->qty - $returnedQty;
                                $itemRefund = (float) ($returnsByItem[$it->product_id]['amount'] ?? 0);
                            @endphp
                            <tr class="align-top">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-900">{{ $it->product?->name }}</p>

                                    @if ((float) $it->discount > 0 || (float) $it->ppn_amount > 0 || $returnedQty > 0)
                                        <div class="mt-1.5 flex flex-wrap gap-1.5">
                                            @if ((float) $it->discount > 0)
                                                <span class="rounded-md bg-amber-50 px-2 py-0.5 text-xs text-amber-700">
                                                    Diskon -Rp {{ number_format($it->discount, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            @if ((float) $it->ppn_amount > 0)
                                                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs text-emerald-700">
                                                    PPN +Rp {{ number_format($it->ppn_amount, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            @if ($returnedQty > 0)
                                                <span class="rounded-md bg-rose-50 px-2 py-0.5 text-xs text-rose-700">
                                                    Diretur {{ $returnedQty }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td class="px-3 py-4 text-center tabular-nums text-slate-700">
                                    @if ($returnedQty > 0)
                                        <span class="font-medium">{{ $remainingQty }}</span>
                                        <span class="text-slate-400">/ {{ $it->qty }}</span>
                                    @else
                                        {{ $it->qty }}
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right tabular-nums">
                                    <p class="font-semibold text-slate-900">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</p>
                                    @if ($itemRefund > 0)
                                        <p class="mt-0.5 text-xs text-rose-600">- Rp {{ number_format($itemRefund, 0, ',', '.') }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Ringkasan --}}
            <div class="border-t border-slate-100 bg-emerald-50/40 p-6">
                <dl class="ml-auto max-w-sm space-y-2 text-sm tabular-nums">
                    <div class="flex justify-between text-slate-600">
                        <dt>Subtotal</dt>
                        <dd>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</dd>
                    </div>

                    @if ($itemDiscountTotal > 0)
                        <div class="flex justify-between text-slate-600">
                            <dt>Diskon item</dt>
                            <dd class="text-amber-700">- Rp {{ number_format($itemDiscountTotal, 0, ',', '.') }}</dd>
                        </div>
                    @endif

                    @if ($transactionDiscount > 0)
                        <div class="flex justify-between text-slate-600">
                            <dt>Diskon transaksi</dt>
                            <dd class="text-amber-700">- Rp {{ number_format($transactionDiscount, 0, ',', '.') }}</dd>
                        </div>
                    @endif

                    @if ($sale->ppn_amount > 0)
                        <div class="flex justify-between text-slate-600">
                            <dt>PPN ({{ number_format($sale->ppn_rate, 0) }}%)</dt>
                            <dd>+ Rp {{ number_format($sale->ppn_amount, 0, ',', '.') }}</dd>
                        </div>
                    @endif

                    <div class="flex items-baseline justify-between border-t border-emerald-200/70 pt-3">
                        <dt class="font-medium text-slate-900">Total</dt>
                        <dd class="text-2xl font-semibold tracking-tight text-slate-900">Rp {{ number_format($sale->total, 0, ',', '.') }}</dd>
                    </div>

                    <div class="flex justify-between font-medium text-emerald-700">
                        <dt>Dibayar</dt>
                        <dd>Rp {{ number_format($sale->paid_total, 0, ',', '.') }}</dd>
                    </div>

                    @unless ($isPaid)
                        <div class="flex justify-between font-semibold text-amber-700">
                            <dt>Sisa tagihan</dt>
                            <dd>Rp {{ number_format($remaining, 0, ',', '.') }}</dd>
                        </div>
                    @endunless
                </dl>
            </div>
        </section>

        {{-- Kolom kanan: pembayaran --}}
        <aside class="space-y-5 xl:sticky xl:top-4">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-baseline justify-between">
                    <h2 class="font-semibold text-slate-900">Pembayaran</h2>
                    <span class="text-xs tabular-nums text-slate-500">{{ $paidPercent }}% terbayar</span>
                </div>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100" role="progressbar"
                     aria-valuenow="{{ $paidPercent }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="h-full rounded-full {{ $isPaid ? 'bg-emerald-500' : 'bg-amber-400' }}" style="width: {{ $paidPercent }}%"></div>
                </div>

                {{-- Riwayat pembayaran --}}
                <ul class="mt-5 space-y-4">
                    @forelse ($sale->payments as $p)
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 grid size-8 shrink-0 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                            </span>
                            <div class="min-w-0 flex-1 text-sm">
                                <div class="flex justify-between gap-3">
                                    <span class="font-medium text-slate-900">{{ $methodLabels[$p->method] ?? strtoupper($p->method) }}</span>
                                    <b class="shrink-0 tabular-nums text-slate-900">Rp {{ number_format($p->amount, 0, ',', '.') }}</b>
                                </div>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    {{ $p->paid_at->format('d M Y, H:i') }} • {{ $p->cashAccount?->name ?? 'Akun lama tidak tercatat' }}
                                </p>
                            </div>
                        </li>
                    @empty
                        <li class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
                            Belum ada pembayaran untuk nota ini.
                        </li>
                    @endforelse
                </ul>
            </section>

            {{-- Form pelunasan --}}
            @if ($canReceivePayment && ! $isPaid)
                <section class="rounded-2xl border border-emerald-200 bg-emerald-50/40 p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">Catat pembayaran</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Sisa tagihan Rp {{ number_format($remaining, 0, ',', '.') }}</p>

                    <form wire:submit="addPayment" class="mt-4 space-y-4">

                        <fieldset>
                            <legend class="mb-2 text-sm font-medium text-slate-700">Metode</legend>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($methodLabels as $value => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="method" value="{{ $value }}" class="peer sr-only">
                                        <span class="block rounded-lg border border-slate-200 bg-white px-2 py-2 text-center text-xs font-medium text-slate-600 transition peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-400 hover:border-emerald-300">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('method') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </fieldset>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label for="payment-amount" class="text-sm font-medium text-slate-700">Nominal</label>
                                <button type="button" x-on:click="$wire.set('amount', {{ (int) $remaining }})"
                                        class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 rounded">
                                    Isi sisa tagihan
                                </button>
                            </div>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-slate-400">Rp</span>
                                <input id="payment-amount" type="number" inputmode="numeric" wire:model="amount" placeholder="0"
                                       class="w-full rounded-lg border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm tabular-nums focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            </div>
                            @error('amount') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="payment-account" class="mb-2 block text-sm font-medium text-slate-700">Masuk ke akun kas</label>
                            <select id="payment-account" wire:model="cashAccountId"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option value="">Pilih akun kas</option>
                                @foreach ($cashAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->type === 'cash' ? 'Tunai' : 'Bank' }})</option>
                                @endforeach
                            </select>
                            @error('cashAccountId') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="addPayment"
                                class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 disabled:opacity-60">
                            <span wire:loading.remove wire:target="addPayment">Catat pelunasan</span>
                            <span wire:loading wire:target="addPayment">Menyimpan…</span>
                        </button>
                    </form>
                </section>
            @endif
        </aside>
    </div>
</div>