<?php

namespace App\Providers;

use App\Filters\BaseFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class BuilderMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Builder::macro('applyFilters', function (BaseFilters $filterClass) {
            return $filterClass->setBuilder($this)
                ->applyFilters();
        });
    }
}
