<?php

namespace App\Transformers;

use App\Models\GameListing;
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
        //
    ];

    public function transform(GameListing $gameListing): array
    {
        return [
            'id'               => $gameListing->id,
            'description'      => $gameListing->description,
            'game_id'          => $gameListing->game_id,
            'game'             => (new GameTransformer())->transform($gameListing->game),
            'owner_id'         => $gameListing->owner_id,
            'owner'            => (new UserTransformer())->transform($gameListing->owner),
            'trade_preference' => $gameListing->trade_preference,
            'platform_id'      => $gameListing->platform_id,
            'platform'         => (new PlatformTransformer())->transform($gameListing->platform),
        ];
    }
}
