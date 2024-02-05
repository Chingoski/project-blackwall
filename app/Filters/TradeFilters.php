<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TradeFilters extends BaseFilters
{
    public function before(): void
    {
        $this->builder->select('trade.*');

        parent::before();
    }

    public function search(string $search): void
    {
        $this->builder->where(function ($query) use ($search) {
            $query->whereExists(function ($query) use ($search) {
                $query->selectRaw('1')
                    ->from('game')
                    ->join('offered_trade_game', 'game.id', '=', 'offered_trade_game.game_id')
                    ->whereColumn('offered_trade_game.trade_id', 'trade.id')
                    ->where('game.name', 'ilike', "%{$search}%");
            });
        });
    }

    public function traderUserId(int $traderUserId): void
    {
        $this->builder->where('trader_user_id', '=', $traderUserId);
    }

    public function ownerId(int $ownerId): void
    {
        $this->builder->join('game_listing', 'trade.game_listing_id', '=', 'game_listing.id')
            ->where('game_listing.owner_id', '=', $ownerId)
            ->when($ownerId != Auth::user()->getKey(), fn($query) => $query->where('trader_user_id', '=', Auth::user()->getKey()));
    }


    public function gameListingId(int $gameListingId): void
    {
        $this->builder->where('game_listing_id', '=', $gameListingId);
    }

    public function status(int $status): void
    {
        $this->builder->where('status', '=', $status);
    }
}
