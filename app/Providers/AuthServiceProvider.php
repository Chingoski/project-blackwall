<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Game;
use App\Models\GameListing;
use App\Models\Genre;
use App\Models\Trade;
use App\Models\User;
use App\Policies\CityPolicy;
use App\Policies\GameListingPolicy;
use App\Policies\GamePolicy;
use App\Policies\GenrePolicy;
use App\Policies\TradePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        City::class        => CityPolicy::class,
        Genre::class       => GenrePolicy::class,
        Game::class        => GamePolicy::class,
        GameListing::class => GameListingPolicy::class,
        Trade::class       => TradePolicy::class,
        User::class        => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
