<?php

namespace App\Policies;

use App\Models\LabOrder;
use App\Models\User;

class LabOrderPolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'kasir', 'optician']);
    }

    public function update(User $user, LabOrder $labOrder): bool
    {
        return $this->inSameActiveStore($user, $labOrder)
            && $this->hasStoreRole($user, ['owner', 'kasir', 'optician']);
    }
}
