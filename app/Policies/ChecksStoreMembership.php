<?php

namespace App\Policies;

use App\Models\User;

trait ChecksStoreMembership
{
    protected function inSameActiveStore(User $user, $model): bool
    {
        $storeId = app('activeStore')->id($user);

        if (! $storeId) {
            return false;
        }

        return $user->belongsToStore($storeId)
            && (int) $model->store_id === (int) $storeId;
    }

    protected function hasStoreRole(User $user, array $roles): bool
    {
        $role = app('activeStore')->role($user);

        return $role && in_array($role, $roles, true);
    }
}
