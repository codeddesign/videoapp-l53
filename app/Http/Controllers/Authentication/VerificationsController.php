<?php

namespace VideoAd\Http\Controllers\Authentication;

use Illuminate\View\View;
use VideoAd\Http\Controllers\Controller;

class VerificationsController extends Controller
{
    /**
     * Show the verify phone page.
     *
     * @return View
     */
    public function verifyPhone() : View
    {
        return view('auth.verify.phone');
    }

    /**
     * Show the verify email page.
     *
     * @return View
     */
    public function verifyEmail() : View
    {
        return view('auth.verify.email');
    }
}