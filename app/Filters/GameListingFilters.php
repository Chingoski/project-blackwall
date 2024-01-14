<?php

namespace App\Filters;

use App\Enums\TradeStatusEnum;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GameListingFilters extends BaseFilters
{
    public function before(): void
    {
        $filters = $this->getFilters();

        $this->builder->join('game', 'game_id', '=', 'game.id')
            ->select('game_listing.*');

        if (!isset($filters['owner_id'])) {
            $this->builder->where('owner_id', '!=', Auth::user()->id);
        }

        if (!isset($filters['order_by'])) {
            $this->builder->orderBy('game_listing.created_at', 'desc');
        }

        if (!isset($filters['available']) && !isset($filters['owner_id']) && !Arr::hasAny($filters, ['finished', 'accepted'])) {
            $this->available(true);
        }

        parent::before();
    }

    public function search(string $search): void
    {
        $this->builder->where('game.name', 'ilike', "%{$search}%");
    }

    public function ownerId(int $ownerId): void
    {
        $this->builder->where('owner_id', '=', $ownerId);
    }

    public function platformId(int $platformId): void
    {
        $this->builder->where('platform_id', '=', $platformId);
    }

    public function cityId(int $cityId): void
    {
        $this->builder->join('user', 'user.id', '=', 'owner_id')
            ->where('user.city_id', '=', $cityId);
    }

    public function orderBy(string $orderBy): void
    {
        match ($orderBy) {
            'alphabetical' => $this->builder->orderBy('game.name'),
            default => $this->builder->orderBy('game_listing.created_at', 'desc'),
        };
    }

    public function tradePreference(string $tradePreference): void
    {
        $this->builder->where('trade_preference', '=', $tradePreference);
    }

    public function available(bool $available): void
    {
        if (!$available) {
            return;
        }

        $this->builder->whereNotExists(function (Builder $query) {
            $query->selectRaw('1')
                ->from('trade')
                ->whereColumn('game_listing_id', 'game_listing.id')
                ->whereNull('deleted_at')
                ->where(function (Builder $query) {
                    $query->whereIn('trade.status', [TradeStatusEnum::Accepted->value, TradeStatusEnum::Finished->value])
                        ->orWhere(function (Builder $query) {
                            $query->where('status', '=', TradeStatusEnum::Pending->value)
                                ->where('trade.trader_user_id', '=', Auth::user()->getKey());
                        });
                });
        });
    }

    public function accepted(bool $accepted): void
    {
        if (!$accepted) {
            return;
        }

        $this->builder->whereExists(function (Builder $query) {
            $query->selectRaw('1')
                ->from('trade')
                ->whereColumn('trade.game_listing_id', 'game_listing.id')
                ->where('trade.status', '=', TradeStatusEnum::Accepted->value)
                ->whereNull('deleted_at');
        });
    }

    public function finished(bool $finished): void
    {
        if (!$finished) {
            return;
        }

        $this->builder->whereExists(function (Builder $query) {
            $query->selectRaw('1')
                ->from('trade')
                ->whereColumn('trade.game_listing_id', 'game_listing.id')
                ->where('trade.status', '=', TradeStatusEnum::Finished->value)
                ->whereNull('deleted_at');
        });
    }

    public function canceled(bool $canceled): void
    {
        if (!$canceled) {
            return;
        }

        $this->builder->whereExists(function (Builder $query) {
            $query->selectRaw('1')
                ->from('trade')
                ->whereColumn('trade.game_listing_id', 'game_listing.id')
                ->where('trade.status', '=', TradeStatusEnum::Canceled->value)
                ->whereNull('deleted_at');
        });
    }
}
