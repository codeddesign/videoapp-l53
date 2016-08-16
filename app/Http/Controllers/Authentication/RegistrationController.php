<?php

namespace VideoAd\Http\Controllers\Authentication;

use Illuminate\View\View;
use VideoAd\Http\Controllers\Controller;

/**
 * @author Adib Hanna <adibhanna@gmail.com>
 * Class RegistrationController
 * @package VideoAd\Http\Controllers\Authentication
 */
class RegistrationController extends Controller
{
    /**
     * Show the registration page.
     *
     * @return View
     */
    public function showRegistrationForm() : View
    {
        return view('auth.register');
    }

    public function register()
    {
        //
    }
}
