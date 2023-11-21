<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Exclude these routes from authentication
     */
    protected array $except = [
        'auth/login',
    ];


    public function handle($request, Closure $next, ...$guards)
    {
        if ((in_array($request->getPathInfo(), $this->except))) {
            return $next($request);
        }

        return parent::handle($request, $next, ...$guards);
    }
}
