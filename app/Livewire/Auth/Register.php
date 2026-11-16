<?php

namespace App\Livewire\Auth;

use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Register extends Component
{
    #[Validate('required|string|max:100')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    #[Validate('required|string|max:150')]
    public string $store_name = '';

    #[Validate('nullable|string|max:30')]
    public string $store_phone = '';

    public function register(): void
    {
        $this->validate();

        DB::transaction(function () {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
            ]);

            $slugBase = Str::slug($this->store_name) ?: 'toko';
            $slug = $slugBase;
            $counter = 1;
            while (Store::where('slug', $slug)->exists()) {
                $slug = $slugBase.'-'.$counter++;
            }

            $store = Store::create([
                'name' => $this->store_name,
                'slug' => $slug,
                'phone' => $this->store_phone ?: null,
            ]);

            $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

            $plan = Plan::where('slug', 'basic')->where('is_active', true)->first()
                ?? Plan::where('is_active', true)->orderBy('id')->first();

            if ($plan) {
                Subscription::create([
                    'store_id' => $store->id,
                    'plan_id' => $plan->id,
                    'status' => 'trial',
                    'started_at' => now()->toDateString(),
                    'ends_at' => now()->addDays(14)->toDateString(),
                ]);
            }

            Auth::login($user);
            session()->regenerate();
        });

        $this->redirectRoute('verification.notice', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
