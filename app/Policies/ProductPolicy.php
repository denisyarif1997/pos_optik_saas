<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function view(User $user, Product $product): bool
    {
        return $this->inSameActiveStore($user, $product);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'stok']);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->inSameActiveStore($user, $product)
            && $this->hasStoreRole($user, ['owner', 'stok']);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->inSameActiveStore($user, $product)
            && $this->hasStoreRole($user, ['owner']);
    }
}
