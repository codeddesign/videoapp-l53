<?php

namespace App\JWT;

use Closure;

class JWTAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app('auth')->authenticate($request);

        $response = $next($request);

        return $response;
    }
}
