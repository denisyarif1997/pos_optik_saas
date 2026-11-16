<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function view(User $user, Sale $sale): bool
    {
        return $this->inSameActiveStore($user, $sale);
    }

    public function receivePayment(User $user, Sale $sale): bool
    {
        return $this->inSameActiveStore($user, $sale)
            && $this->hasStoreRole($user, ['owner', 'kasir']);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'kasir']);
    }

    public function refund(User $user, Sale $sale): bool
    {
        return $this->inSameActiveStore($user, $sale)
            && $this->hasStoreRole($user, ['owner']);
    }
}
