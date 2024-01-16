<?php

namespace App\LogicValidators\Trade;

use App\Models\Trade;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CheckIfTradeIsAlreadyConfirmedByUserLogicValidator
{
    public function __construct(protected User $user, protected Trade $trade)
    {
    }

    public function validate(): void
    {
        $tradeAlreadyConfirmed = match (true) {
            $this->trade->belongsToTrader($this->user) => $this->trade->trader_confirmed,
            default => $this->trade->trader_confirmed,
        };

        if (!$tradeAlreadyConfirmed) {
            return;
        }

        throw new UnprocessableEntityHttpException('You have already confirmed the trade.');
    }
}
