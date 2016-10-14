<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAccount
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
        if (! $request->user()->verified()) {
            return redirect()->route('verify.phone');
        }

        return $next($request);
    }
}
