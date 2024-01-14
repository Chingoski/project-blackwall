<?php

namespace App\LogicValidators\Trade;

use App\Enums\TradeStatusEnum;
use App\Models\Trade;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CheckIfTradeCanBeCanceledLogicValidator
{
    public function __construct(protected Trade $trade)
    {

    }

    #[NoReturn] public function validate(): void
    {
        match ((int)$this->trade->status) {
            TradeStatusEnum::Canceled->value => throw new UnprocessableEntityHttpException('The trade has already been canceled.'),
            TradeStatusEnum::Finished->value => throw new UnprocessableEntityHttpException('The trade has finished and is completed.'),
            default => null,
        };
    }
}
