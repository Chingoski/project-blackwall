<?php

namespace App\Transformers;

use App\Models\Genre;
use League\Fractal\TransformerAbstract;

class GenreTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        //
    ];

    protected array $availableIncludes = [
        //
    ];

    /**
     * @param Genre $genre
     * @return array
     */
    public function transform(Genre $genre): array
    {
        return [
            'id'   => $genre->getKey(),
            'name' => $genre->name,
            'slug' => $genre->slug,
        ];
    }
}
