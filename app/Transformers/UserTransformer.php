<?php

namespace App\Transformers;

use App\Models\User;
use Carbon\Carbon;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
        'city',
    ];

    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'id'            => $user->getKey(),
            'first_name'    => ucwords($user->first_name),
            'last_name'     => ucwords($user->last_name),
            'full_name'     => ucwords($user->full_name),
            'email'         => $user->email,
            'phone_number'  => $user->phone_number,
            'address'       => $user->address,
            'date_of_birth' => Carbon::parse($user->date_of_birth)->format('m/d/Y'),
            'city_id'       => $user->city_id,
            'city'          => (new CityTransformer())->transform($user->city),
        ];
    }

    public function includeCity(User $user): Item
    {
        return $this->item($user->city, new CityTransformer());
    }
}
