<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;

class ActiveStore
{
    public function id(?User $user = null): ?int
    {
        $user ??= auth()->user();

        return $user?->storeId();
    }

    public function get(?User $user = null): ?Store
    {
        $user ??= auth()->user();

        return $user?->store();
    }

    public function check(?User $user = null): bool
    {
        return $this->get($user) !== null;
    }

    public function role(?User $user = null): ?string
    {
        $user ??= auth()->user();
        $store = $this->get($user);

        return $store?->pivot->role;
    }
}
