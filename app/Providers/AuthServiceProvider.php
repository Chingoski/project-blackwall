<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Genre;
use App\Policies\CityPolicy;
use App\Policies\GenrePolicy;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        City::class  => CityPolicy::class,
        Genre::class => GenrePolicy::class,
        Tag::class   => TagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
