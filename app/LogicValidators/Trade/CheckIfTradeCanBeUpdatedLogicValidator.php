<?php

namespace App\LogicValidators\Trade;

use App\Enums\TradeStatusEnum;
use App\Models\GameListing;
use App\Models\Trade;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CheckIfTradeCanBeUpdatedLogicValidator
{
    public function __construct(protected Trade $trade)
    {
    }

    public function validate(): void
    {
        if ($this->trade->status == TradeStatusEnum::Pending->value) {
            return;
        }

        throw new UnprocessableEntityHttpException('Trade can not be updated.');
    }
}
