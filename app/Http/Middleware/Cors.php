<?php

namespace VideoAd\Http\Middleware;

use Closure;

/**
 * @author Adib Hanna <adibhanna@gmail.com>
 * Class Cors
 * @package VideoAd\Http\Middleware
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
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Max-Age', '1000')
            ->header('Access-Control-Allow-Credentials', 'false')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization');
    }
}
