<div class="space-y-5">
    <div class="flex flex-wrap items-center gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Grid Stok Lensa</h1>
            <p class="text-xs text-slate-500">
                Matriks stok {{ $product->name }} (SPH × CYL, ADD {{ number_format($addVal ?? 0, 2) }})
                • <span class="font-semibold">{{ $lowCount }} menipis</span> • <span class="{{ $outCount ? 'text-red-600' : 'text-slate-600' }}">{{ $outCount }} habis</span>
            </p>
        </div>
        <a href="{{ route('inventory.index') }}" class="ml-auto bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg"> Kembali</a>
    </div>

    <!-- Ringkasan stok -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
            <div class="p-3 rounded-lg bg-slate-50">
                <div class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Total sel</div>
                <div class="text-lg font-semibold">{{ count($matrix) }}</div>
            </div>
            <div class="p-3 rounded-lg bg-emerald-50">
                <div class="text-[11px] uppercase tracking-wider text-emerald-700 font-bold">Tersedia</div>
                <div class="text-lg font-semibold text-emerald-700">{{ count($matrix) - $outCount }}</div>
            </div>
            <div class="p-3 rounded-lg bg-amber-50">
                <div class="text-[11px] uppercase tracking-wider text-amber-700 font-bold">Stok menipis</div>
                <div class="text-lg font-semibold text-amber-700">{{ $lowCount }}</div>
            </div>
            <div class="p-3 rounded-lg bg-red-50">
                <div class="text-[11px] uppercase tracking-wider text-red-700 font-bold">Stok habis</div>
                <div class="text-lg font-semibold text-red-700">{{ $outCount }}</div>
            </div>
        </div>
    </div>

    <!-- Grid matriks: baris CYL, kolom SPH -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200">
                        <th class="px-3 py-2.5 text-[11px] font-bold text-slate-500 uppercase sticky top-0 bg-slate-100">CYL &darr; / SPH &rarr;</th>
                        @foreach ($sphRange as $sph)
                            <th class="px-3 py-2.5 text-[11px] font-bold text-slate-600 uppercase border-l border-slate-200">
                                {{ number_format($sph, 2) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cylRange as $cyl)
                        <tr class="border-b border-slate-100">
                            <th class="px-3 py-2.5 text-[11px] font-bold text-slate-600 uppercase border-r border-slate-200 bg-slate-50 sticky left-0">
                                {{ number_format($cyl, 2) }}
                            </th>
                            @foreach ($sphRange as $sph)
                                <?php
                                $key = number_format($sph, 2) . '|' . number_format($cyl, 2);
                                $cell = $matrix[$key] ?? ['stock_qty' => 0, 'min_stock_alert' => 2];
                                $isEditing = $editingKey === $key;
                                $isLow = (int) $cell['stock_qty'] <= (int) ($cell['min_stock_alert'] ?? 2);
                                $isOut = (int) $cell['stock_qty'] === 0;
                                $badgeClass = $isOut ? 'bg-red-100 text-red-700' : ($isLow ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');
                                $bgClass = $isEditing ? 'bg-amber-50' : 'bg-white hover:bg-slate-50';
                                ?>
                                <td class="px-3 py-3 border-r border-slate-100 {{ $bgClass }} transition group">
                                    @if ($isEditing)
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- wire:model (tanpa .live) agar nilai hanya tersimpan saat Simpan diklik, tidak re-render setiap ketik -->
                                            <input type="number" wire:model="pendingQty" min="0" class="w-16 text-center rounded border border-amber-300 bg-amber-50 px-2 py-1.5 text-sm font-bold focus:ring-2 focus:ring-amber-500 outline-none" x-on:focus="$el.select()" />
                                            <button wire:click="saveStock('{{ $key }}')" class="px-2.5 py-1.5 rounded bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">Simpan</button>
                                            <button wire:click="cancelEditing" class="px-2.5 py-1.5 rounded bg-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-300">Batal</button>
                                        </div>
                                    @else
                                        <button wire:click="startEditing('{{ $key }}')" class="w-full py-2 rounded-lg {{ $badgeClass }} text-xs font-bold text-left pl-3 pr-2 uppercase tracking-wider transition {{ $isOut ? 'animate-pulse' : '' }}" title="Stok: {{ $cell['stock_qty'] }}">
                                            <span class="block">{{ $cell['stock_qty'] }}</span>
                                            <span class="block text-[9px] font-normal opacity-70 mt-0.5">{{ $isOut ? 'HABIS' : ($isLow ? 'menipis' : 'ok') }}</span>
                                        </button>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($outCount)
        <div class="mt-3 bg-red-50 border border-red-100 rounded-lg p-3">
            <div class="text-xs font-bold text-red-800 uppercase mb-1">Perhatian</div>
            <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                @foreach ($matrix as $key => $cell)
                    @if ((int) $cell['stock_qty'] === 0)
                        <li>SPH {{ $cell['sph'] }} / CYL {{ $cell['cyl'] }}: stok habis.</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif

    <!-- BAGIAN INI DIPINDAHKAN KE DALAM DIV UTAMA -->
    <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-xs text-amber-700">
        Klik sel untuk mengubah stok secara langsung. Sel merah = stok habis, sel oranye = stok menipis (&le; minimum peringatan).
    </div>
</div>