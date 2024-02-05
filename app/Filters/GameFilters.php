<?php

namespace App\Filters;

class GameFilters extends BaseFilters
{
    public function before(): void
    {
        $this->builder
            ->select(['game.*', 'platform.id as platform_id', 'platform.name as platform_name', 'platform.slug as platform_slug'])
            ->join('game_platform', 'game.id', '=', 'game_id')
            ->join('platform', 'platform.id', '=', 'platform_id')
            ->whereNotNull('release_date')
            ->where('release_date', '<', now())
            ->orderBy('release_date', 'desc');
    }

    public function search(string $search): void
    {
        $this->builder->where('game.name', 'ilike', "%{$search}%");
    }

    public function genre_ids(array $genreIds): void
    {
        $this->builder->whereExists(function ($query) use ($genreIds) {
            $query->selectRaw('1')
                ->from('game_genre')
                ->whereIn('genre_id', $genreIds)
                ->whereColumn('game_id', 'game.id');
        });
    }

    public function platform_ids(array $platformIds): void
    {
        $this->builder->whereIn('platform_id', $platformIds);
    }
}
