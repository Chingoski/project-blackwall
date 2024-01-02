<?php

namespace App\Filters;

class GameFilters extends BaseFilters
{
    public function before(): void
    {
        $this->builder
            ->whereNotNull('release_date')
            ->where('release_date', '<', now())
            ->orderBy('release_date', 'desc');
    }

    public function search(string $search): void
    {
        $this->builder->where('name', 'ilike', "%{$search}%");
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
        $this->builder->whereExists(function ($query) use ($platformIds) {
            $query->selectRaw('1')
                ->from('game_platform')
                ->whereIn('platform_id', $platformIds)
                ->whereColumn('game_id', 'game.id');
        });
    }
}
