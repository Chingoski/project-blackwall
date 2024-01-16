<?php

namespace App\LogicValidators\Trade;

use App\Enums\TradeStatusEnum;
use App\Models\GameListing;
use App\Models\Trade;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CheckIfUserAlreadyRequestedTradeLogicValidator
{
    public function __construct(protected User $user, protected GameListing $gameListing)
    {
    }

    public function validate(): void
    {
        $tradeAlreadyExists = Trade::query()
            ->where('trader_user_id', '=', $this->user->getKey())
            ->where('game_listing_id', '=', $this->gameListing->getKey())
            ->where('status', '=', TradeStatusEnum::Pending->value)
            ->exists();

        if (!$tradeAlreadyExists) {
            return;
        }

        throw new UnprocessableEntityHttpException('You have already requested a trade for this game listing.');
    }
}
