<?php

namespace App\Livewire\Billing;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function upgrade(int $planId): void
    {
        $store = app('activeStore')->get();
        abort_unless($store && app('activeStore')->role() === 'owner', 403);

        $plan = Plan::where('is_active', true)->findOrFail($planId);

        DB::transaction(function () use ($store, $plan) {
            $current = $store->currentSubscription();
            if ($current) {
                $current->update(['status' => 'cancelled']);
            }

            Subscription::create([
                'store_id' => $store->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'started_at' => now()->toDateString(),
                'ends_at' => now()->addDays($plan->duration_days)->toDateString(),
            ]);
        });

        session()->flash('success', 'Paket berhasil diperbarui.');
    }

    public function render()
    {
        $store = app('activeStore')->get();
        abort_unless($store, 403);

        return view('livewire.billing.index', [
            'store' => $store,
            'current' => $store->currentSubscription(),
            'plans' => Plan::where('is_active', true)->orderBy('price')->get(),
        ])->title('Langganan');
    }
}
