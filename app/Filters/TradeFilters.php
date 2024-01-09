<?php

namespace App\Filters;

use Illuminate\Database\Query\Builder;

class TradeFilters extends BaseFilters
{
    public function before(): void
    {
        $this->builder->select('trade.*');

        parent::before();
    }

    public function search(string $search): void
    {
        $this->builder->where(function (Builder $query) use ($search) {
            $query->whereExists(function (Builder $query) use ($search) {
                $query->selectRaw('1')
                    ->from('game')
                    ->join('offered_trade_game', 'game.id', '=', 'offered_trade_game.game_id')
                    ->whereColumn('offered_trade_game.trade_id', 'trade.id')
                    ->where('game.name', 'ilike', "%{$search}%");
            });
        });
    }

    public function trader_user_id(int $traderUserId): void
    {
        $this->builder->where('trader_user_id', '=', $traderUserId);
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
