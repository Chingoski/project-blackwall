<?php

namespace App\Transformers;

use App\Models\Game;
use Carbon\Carbon;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class GameTransformer extends TransformerAbstract
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
        'genres',
        'platforms',
    ];

    /**
     * @param Game $game
     * @return array
     */
    public function transform(Game $game): array
    {
        $genresData = [];

        foreach ($game->genres as $genre) {
            $genresData[] = (new GenreTransformer())->transform($genre);
        }

        return [
            'id'           => $game->getKey(),
            'name'         => $game->name,
            'slug'         => $game->slug,
            'thumbnail'    => $game->thumbnail,
            'rating'       => $game->rating,
            'release_date' => isset($game->release_date) ? Carbon::parse($game->release_date)->format('m/d/Y') : null,
            'genres'       => $genresData,
        ];
    }

    public function includeGenres(Game $game): Collection
    {
        return $this->collection($game->genres, new GenreTransformer());
    }

    public function includePlatforms(Game $game): Collection
    {
        return $this->collection($game->platforms, new PlatformTransformer());
    }
}
