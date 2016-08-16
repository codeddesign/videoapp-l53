<?php

namespace VideoAd\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::user()->verified_phone) {
            return redirect()->route('verify.phone');
        }

        if (!Auth::user()->verified_email) {
            return redirect()->route('verify.email');
        }

        return $next($request);
    }
}
