<?php

namespace App\LogicValidators\Trade;

use App\Models\GameListing;
use App\Models\Trade;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserCanSearchTradesForGameListingLogicValidator
{
    public function validate(User $user, GameListing $gameListing): void
    {
        if ($gameListing->owner_id == $user->getKey()) {
            return;
        }

        $tradeExists = Trade::query()
            ->where('trader_user_id', '=', $user->getKey())
            ->where('game_listing_id', '=', $gameListing->getKey())
            ->exists();

        if ($tradeExists) {
            return;
        }

        throw new UnprocessableEntityHttpException('You cannot view trade offers for that listing');
    }
}
