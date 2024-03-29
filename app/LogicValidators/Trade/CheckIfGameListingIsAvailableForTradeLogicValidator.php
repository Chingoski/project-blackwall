<?php

namespace App\LogicValidators\Trade;

use App\Enums\TradeStatusEnum;
use App\Models\GameListing;
use App\Models\Trade;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CheckIfGameListingIsAvailableForTradeLogicValidator
{
    public function __construct(protected GameListing $gameListing)
    {
    }

    public function validate(): void
    {
        $isGameListingAvailable = Trade::query()
            ->where('game_listing_id', '=', $this->gameListing->getKey())
            ->whereIn('status', [TradeStatusEnum::Accepted->value, TradeStatusEnum::Finished->value])
            ->doesntExist();

        if ($isGameListingAvailable) {
            return;
        }

        throw new UnprocessableEntityHttpException('The specific game listing is not available for trade.');
    }
}
