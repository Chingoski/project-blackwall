<?php

namespace App\Filters;

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

    public function tradePreference(string $tradePreference)
    {
        $this->builder->where('trade_preference', '=', $tradePreference);
    }
}
