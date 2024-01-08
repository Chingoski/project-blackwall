<?php

namespace App\Transformers;

use App\Enums\TradeStatusEnum;
use App\Models\Trade;
use League\Fractal\TransformerAbstract;

class TradeTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Trade $trade
     * @return array
     */
    public function transform(Trade $trade): array
    {
        return [
            'id'              => $trade->getKey(),
            'game_listing_id' => $trade->game_listing_id,
            'trader_user_id'  => $trade->trader_user_id,
            'status'          => TradeStatusEnum::getName($trade->status),
        ];
    }
}
