<?php

namespace App\Policies;

use App\Models\OpticalOrder;
use App\Models\User;

class OpticalOrderPolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function view(User $user, OpticalOrder $order): bool
    {
        return $this->inSameActiveStore($user, $order);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'kasir', 'optician']);
    }

    public function update(User $user, OpticalOrder $order): bool
    {
        return $this->inSameActiveStore($user, $order)
            && $this->hasStoreRole($user, ['owner', 'kasir', 'optician']);
    }
}
