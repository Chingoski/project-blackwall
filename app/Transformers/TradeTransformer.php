<?php

namespace App\Transformers;

use App\Enums\TradeStatusEnum;
use App\Models\GameListing;
use App\Models\Trade;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
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
        'trader',
        'game_listing',
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
            'id'               => $trade->getKey(),
            'game_listing_id'  => $trade->game_listing_id,
            'trader_user_id'   => $trade->trader_user_id,
            'offered_amount'   => number_format($trade->offered_amount ?? 0, 2, '.', ','),
            'offered_games'    => $this->generateOfferedGamesResponse($trade),
            'owner_confirmed'  => $trade->owner_confirmed,
            'trader_confirmed' => $trade->trader_confirmed,
            'status'           => TradeStatusEnum::getName($trade->status),
        ];
    }

    protected function generateOfferedGamesResponse(Trade $trade): array
    {
        $response = [];

        foreach ($trade->offered_games as $offeredGame) {
            $gameResponse = (new GameTransformer())->transform($offeredGame->game);
            $gameResponse['platform'] = (new PlatformTransformer())->transform($offeredGame->platform);

            $response[] = $gameResponse;
        }

        return $response;
    }

    public function includeTrader(Trade $trade): Item
    {
        return $this->item($trade->trader, new UserTransformer());
    }

    public function includeGameListing(Trade $trade): Item
    {
        return $this->item($trade->game_listing, new GameListingTransformer());
    }
}
