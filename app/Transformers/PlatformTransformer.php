<?php

namespace App\Transformers;

use App\Models\Platform;
use League\Fractal\TransformerAbstract;

class PlatformTransformer extends TransformerAbstract
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
    public array $availableIncludes = [

    ];

    /**
     * A Fractal transformer.
     *
     * @param Platform $platform
     * @return array
     */
    public function transform(Platform $platform): array
    {
        return [
            'id'   => $platform->getKey(),
            'name' => $platform->name,
            'slug' => $platform->slug,
        ];
    }
}
