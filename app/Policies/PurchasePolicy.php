<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;

class PurchasePolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function view(User $user, Purchase $purchase): bool
    {
        return $this->inSameActiveStore($user, $purchase);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'stok']);
    }
}
