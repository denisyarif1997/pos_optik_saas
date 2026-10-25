<?php

namespace App\Policies;

use App\Models\User;

class StockPolicy
{
    use ChecksStoreMembership;

    public function manage(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'stok']);
    }
}
