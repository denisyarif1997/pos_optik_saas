<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->inSameActiveStore($user, $customer);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner', 'kasir', 'optician']);
    }

    public function update(User $user, Customer $customer): bool
    {
        return $this->inSameActiveStore($user, $customer)
            && $this->hasStoreRole($user, ['owner', 'kasir', 'optician']);
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $this->inSameActiveStore($user, $customer)
            && $this->hasStoreRole($user, ['owner']);
    }
}
