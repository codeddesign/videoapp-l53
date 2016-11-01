<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class IsAdmin
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (! auth()->user()->admin) {
            throw new AuthenticationException;
        }

        return $next($request);
    }
}
