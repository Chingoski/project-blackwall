<?php

namespace App\Transformers;

use App\Models\OfferedTradeGame;
use League\Fractal\TransformerAbstract;

class OfferedTradeGameTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     * @var array
     */
    protected array $availableIncludes = [

    ];

    /**
     * @param OfferedTradeGame $game
     * @return array
     */
    public function transform(OfferedTradeGame $game): array
    {
        return [
            'id'       => $game->getKey(),
            'game'     => (new GameTransformer())->transform($game->game),
            'platform' => (new PlatformTransformer())->transform($game->platform),
            'trade_id' => $game->trade_id,
        ];
    }
}
