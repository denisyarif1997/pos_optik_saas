<div class="space-y-4">
    <x-page-header title="Pengeluaran" description="Catat dan kelola pengeluaran operasional toko">
        <x-slot:actions>
            <button wire:click="showForm = ! showForm" class="btn-primary px-4 py-2 text-sm">
                @if ($showForm)
                    <span>&times;</span> Batal
                @else
                    <span>&plus;</span> Catat Pengeluaran
                @endif
            </button>
        </x-slot:actions>
    </x-page-header>

    {{-- Ringkasan bulan ini --}}
    <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-4 mb-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-emerald-600 font-medium">Pengeluaran Bulan Ini</p>
                <p class="text-lg font-bold text-emerald-800">Rp {{ number_format($monthTotal, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    {{-- Form input --}}
    @if ($showForm)
        <div class="card p-6 mb-6 border-emerald-soft bg-emerald-bg/30 rounded-2xl shadow-card space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-emerald-soft">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald text-white flex items-center justify-center shadow-xs">
                        <x-icon name="wallet" class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-text-main">Catat Pengeluaran Baru</h2>
                        <p class="text-xs text-text-secondary">Pengeluaran akan tercatat dalam laporan laba &amp; rugi</p>
                    </div>
                </div>
                <button wire:click="showForm = false" class="btn-ghost px-3 py-1.5 text-xs">Batal</button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="label">Tanggal <span class="text-error">*</span></label>
                    <input type="date" wire:model="spent_at" class="input @error('spent_at') border-error @enderror">
                    @error('spent_at')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Kategori <span class="text-error">*</span></label>
                    <select wire:model="category" class="input @error('category') border-error @enderror">
                        <option value="operasional">Operasional</option>
                        <option value="gaji">Gaji</option>
                        <option value="sewa">Sewa</option>
                        <option value="listrik">Listrik &amp; Air</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    @error('category')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Deskripsi / Keterangan</label>
                    <input type="text" wire:model="description" placeholder="Contoh: Pembayaran tagihan PLN bulan berjalan" class="input @error('description') border-error @enderror">
                    @error('description')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Nominal (Rp) <span class="text-error">*</span></label>
                    <input type="number" step="0.01" wire:model="amount" placeholder="0" class="input @error('amount') border-error @enderror">
                    @error('amount')
                        <p class="text-[11px] text-error mt-1 flex items-center gap-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-emerald-soft">
                    <button type="button" wire:click="showForm = false" class="btn-ghost px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="btn-primary px-5 py-2 text-sm font-semibold">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>
    @endif

    {{-- Tabel riwayat --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">Deskripsi</th>
                        <th class="py-3 px-4 text-left w-32">Kategori</th>
                        <th class="py-3 px-4 text-left w-20">Tanggal</th>
                        <th class="py-3 px-4 text-right w-24">Nominal</th>
                        <th class="py-3 px-4 text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($expenses as $e)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-text-main">{{ $e->description }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ ucfirst($e->category) }}</td>
                            <td class="py-3 px-4 text-[11px] text-text-secondary">{{ $e->spent_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-[11px] font-semibold text-red-600 text-right">Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('expenses.edit', $e->id) }}" wire:navigate class="text-[11px] font-semibold text-emerald-dark hover:text-emerald px-2 py-1 bg-emerald-bg rounded">Ubah</a>
                                    <button wire:click="delete({{ $e->id }})" wire:confirm="Hapus?" class="text-[11px] font-semibold text-error hover:opacity-70">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">Belum ada pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


