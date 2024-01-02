<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\GameListing;
use App\Models\User;

class GameListingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function find(User $user, GameListing $gameListing): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function update(User $user, GameListing $gameListing): bool
    {
        return $gameListing->owner_id == $user->getKey();
    }
}
