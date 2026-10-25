<div class="space-y-4">
    <div class="card p-4">
        <h2 class="text-sm font-semibold mb-2">Status Langganan</h2>
        @if ($current)
            <div class="text-sm">
                <div>Paket: <span class="font-semibold">{{ $current->plan?->name }}</span></div>
                <div>Status: <span class="capitalize">{{ $current->status }}</span></div>
                <div>Berakhir: {{ $current->ends_at?->translatedFormat('d M Y') ?? '-' }}</div>
                @if ($current->isExpired())
                    <div class="text-red-600 mt-1">Langganan telah berakhir.</div>
                @endif
            </div>
        @else
            <div class="text-sm text-slate-500">Belum ada langganan.</div>
        @endif
    </div>

    <div class="card p-4">
        <h2 class="text-sm font-semibold mb-3">Pilih Paket</h2>
        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($plans as $plan)
                <div class="border rounded-lg p-3 {{ $current?->plan_id === $plan->id ? 'border-slate-900' : 'border-slate-200' }}">
                    <div class="font-semibold">{{ $plan->name }}</div>
                    <div class="text-xs text-slate-500">Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_days }} hari</div>
                    @if (is_array($plan->features))
                        <ul class="mt-2 text-xs text-slate-600 list-disc pl-4">
                            @foreach ($plan->features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <button wire:click="upgrade({{ $plan->id }})" class="mt-3 bg-slate-900 hover:bg-black text-white text-sm font-semibold px-4 py-2 rounded-lg" @disabled($current?->plan_id === $plan->id)>
                        {{ $current?->plan_id === $plan->id ? 'Paket Aktif' : 'Pilih' }}
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>