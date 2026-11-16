<div class="grid xl:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3">
        <h1 class="font-semibold">Terbitkan Garansi</h1>
        <select wire:model="customer_id" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
            <option value="">— Pelanggan —</option>
            @foreach ($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
        </select>
        @error('customer_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        <input type="date" wire:model="valid_until" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
        <input type="text" wire:model="claim_notes" placeholder="Catatan klaim (opsional)" class="w-full rounded-lg bg-slate-50 border border-emerald-100 px-4 py-2.5 text-sm">
        <button wire:click="save" class="w-full bg-slate-900 hover:bg-black text-white text-sm font-semibold">Terbitkan →</button>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-semibold mb-3">Daftar Garansi</h2>
        @forelse ($warranties as $w)
            <div class="flex items-center gap-2 text-sm py-2 border-b border-slate-50 last:border-0">
                <span class="flex-1"><b>{{ $w->code }}</b> <span class="text-slate-400">• s/d {{ $w->valid_until?->format('d M Y') }}</span></span>
                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ $w->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $w->status }}</span>
                @if ($w->status === 'active')<button wire:click="claim({{ $w->id }})" class="text-xs font-bold text-amber-600">Klaim</button>@endif
<a href="{{ route('warranties.edit', $w->id) }}" wire:navigate class="text-xs font-bold text-slate-500">Ubah</a>
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-4">Belum ada garansi.</p>
        @endforelse
    </div>
</div>


