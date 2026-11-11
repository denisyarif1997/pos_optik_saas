<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        $key = 'login:'.$this->email;
        $maxAttempts = 5;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            $this->addError('email', 'Terlalu banyak percobaan login. Silakan coba lagi dalam '.$seconds.' detik.');

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key, $decayMinutes * 60);

            $this->addError('email', 'Email atau password salah.');

            return;
        }

        RateLimiter::clear($key);

        session()->regenerate();

        $user = Auth::user()->fresh();

        if (! app('activeStore')->check($user)) {
            $this->redirectRoute('stores.create', navigate: true);

            return;
        }

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
