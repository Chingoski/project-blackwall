<?php

namespace App\Transformers;

use App\Models\Tag;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        //
    ];

    protected array $availableIncludes = [
        //
    ];

    /**
     * @param Tag $tag
     * @return array
     */
    public function transform(Tag $tag): array
    {
        return [
            'id'   => $tag->getKey(),
            'name' => $tag->name,
            'slug' => $tag->slug,
        ];
    }
}
