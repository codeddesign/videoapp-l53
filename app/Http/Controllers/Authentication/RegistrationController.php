<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\View\View;
use App\Events\VerifyAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;

/**
 * @author Coded Design
 *
 * @package App\Http\Controllers\Authentication
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

    /**
     * Register the user.
     *
     * @param RegistrationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegistrationRequest $request) : RedirectResponse
    {
        // Register the user if the data is valid.
        // the 'register' method is added in the 'RegistrationRequest'
        // form request, just to add more clarity.
        $user = $request->register();

        // send verification email and phone.
        event(new VerifyAccount($user));

        // login the created user.
        Auth::login($user);

        // login the user and redirect to the phone verification page.
        return redirect()->route('verify.phone');
    }
}
