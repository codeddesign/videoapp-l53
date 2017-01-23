<?php

namespace App\Http\Middleware;

use Closure;

/**
 */
class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Max-Age', 0)
            ->header('Access-Control-Allow-Credentials', 'false')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $response;
    }
}
