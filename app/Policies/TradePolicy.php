<?php

namespace App\Policies;

use App\Models\Trade;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class TradePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user): bool
    {
        return true;
    }

    public function find(User $user, Trade $trade): bool
    {
        if ($trade->trader_user_id == $user->getKey()) {
            return true;
        }

        return $trade->game_listing->owner_id == $user->getKey();
    }

    public function create(User $user): bool
    {
        $userId = Request::get('trader_user_id');

        return $userId == $user->getKey();
    }
}
