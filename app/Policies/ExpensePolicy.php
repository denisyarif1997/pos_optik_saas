<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    use ChecksStoreMembership;

    public function viewAny(User $user): bool
    {
        return app('activeStore')->check($user);
    }

    public function view(User $user, Expense $expense): bool
    {
        return $this->inSameActiveStore($user, $expense);
    }

    public function create(User $user): bool
    {
        return app('activeStore')->check($user)
            && $this->hasStoreRole($user, ['owner']);
    }

    public function update(User $user, Expense $expense): bool
    {
        return $this->inSameActiveStore($user, $expense)
            && $this->hasStoreRole($user, ['owner']);
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $this->inSameActiveStore($user, $expense)
            && $this->hasStoreRole($user, ['owner']);
    }
}
