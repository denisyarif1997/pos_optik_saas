<?php

namespace App\Policies;

use App\Models\ReturnModel;
use App\Models\User;

class ReturnPolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function view(User $user, ReturnModel $retur): bool
    {
        return $this->inSameActiveStore($user, $retur);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'kasir']);
    }

    public function approve(User $user, ReturnModel $retur): bool
    {
        return $this->inSameActiveStore($user, $retur)
            && $this->hasStoreRole($user, ['owner']);
    }
}
