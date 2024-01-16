<?php

namespace App\Policies;

use App\Enums\TradeStatusEnum;
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
        if ($trade->belongsToTrader($user)) {
            return true;
        }

        return $trade->belongsToOwner($user);
    }

    public function create(User $user): bool
    {
        $userId = Request::get('trader_user_id');

        return $userId == $user->getKey();
    }

    public function update(User $user, Trade $trade): bool
    {
        if ($trade->belongsToTrader($user)) {
            return true;
        }

        return $trade->belongsToOwner($user);
    }

    public function accept(User $user, Trade $trade): bool
    {
        return $trade->belongsToOwner($user) && $trade->status == TradeStatusEnum::Pending->value;
    }

    public function confirm(User $user, Trade $trade): bool
    {
        return $trade->belongsToTrader($user) || $trade->belongsToOwner($user);
    }

    public function cancel(User $user, Trade $trade): bool
    {
        return $trade->belongsToTrader($user) || $trade->belongsToOwner($user);
    }
}
