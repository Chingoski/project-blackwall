<?php

namespace App\Transformers;

use App\Models\GameListing;
use App\Models\Trade;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class GameListingTransformer extends TransformerAbstract
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
        'finished_trade',
    ];

    public function transform(GameListing $gameListing): array
    {
        return [
            'id'                         => $gameListing->getKey(),
            'description'                => $gameListing->description,
            'game_id'                    => $gameListing->game_id,
            'game'                       => (new GameTransformer())->transform($gameListing->game),
            'owner_id'                   => $gameListing->owner_id,
            'owner'                      => (new UserTransformer())->transform($gameListing->owner),
            'trade_preference'           => $gameListing->trade_preference,
            'platform_id'                => $gameListing->platform_id,
            'platform'                   => (new PlatformTransformer())->transform($gameListing->platform),
            'pending_trade_offers_count' => $gameListing->pending_trade_offers_count ?? 0,
            'has_accepted_trade_offer'   => (bool)$gameListing->has_accepted_trade_offer,
        ];
    }

    public function includeFinishedTrade(GameListing $gameListing): Item
    {
        return $this->item($gameListing->finished_trade, new TradeTransformer());
    }
}
