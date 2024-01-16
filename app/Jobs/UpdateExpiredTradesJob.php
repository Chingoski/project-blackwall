<?php

namespace App\Jobs;

use App\Enums\TradeStatusEnum;
use App\Models\Trade;

class UpdateExpiredTradesJob extends BaseJob
{
    public function handle(): void
    {
        Trade::query()
            ->whereNotIn('status', [TradeStatusEnum::Canceled->value, TradeStatusEnum::Expired->value, TradeStatusEnum::Finished->value])
            ->where('created_at', '<', now()->subDays(14))
            ->whereNull('deleted_at')
            ->update([
                'owner_confirmed'  => false,
                'trader_confirmed' => false,
                'status'           => TradeStatusEnum::Expired->value,
            ]);
    }
}
