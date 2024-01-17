<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function find(User $authUser, User $user): bool
    {
        return true;
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->getKey() == $user->getKey();
    }
}
