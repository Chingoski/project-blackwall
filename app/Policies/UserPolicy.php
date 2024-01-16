<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function find(User $authUser, User $user): bool
    {
        return true;
    }
}
