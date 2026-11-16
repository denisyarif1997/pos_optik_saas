<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    use ChecksStoreMembership;

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'stok']);
    }

    public function update(User $user, Category $category): bool
    {
        return $this->inSameActiveStore($user, $category)
            && $this->hasStoreRole($user, ['owner', 'stok']);
    }
}
