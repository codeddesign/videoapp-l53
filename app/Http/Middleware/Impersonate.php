<?php

namespace App\Http\Middleware;

use Closure;

class Impersonate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $impersonate = $request->header('ad3-impersonate');

        if ($impersonate === null) {
            return $next($request);
        }

        app('auth')->guard()->impersonate($impersonate);

        return $next($request);
    }
}
