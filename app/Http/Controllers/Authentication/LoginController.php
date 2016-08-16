<?php

namespace VideoAd\Http\Controllers\Authentication;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use VideoAd\Http\Requests\LoginRequest;
use VideoAd\Http\Controllers\Controller;

/**
 * @author Adib Hanna <adibhanna@gmail.com>
 * Class LoginController
 * @package VideoAd\Http\Controllers\Authentication
 */
class LoginController extends Controller
{
    /**
     * Show the login page.
     *
     * @return View
     */
    public function showLoginForm() : View
    {
        return view('auth.login');
    }

    /**
     * Login the user.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request) : RedirectResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return redirect()->back()->withInput()->withErrors([
                $request->get('email') => 'Credentials do not match our records'
            ]);
        }

        if (!Auth::user()->verified_phone) {
            // @todo create an even to send a sms message and verify the user
            return redirect()->route('verify.phone');
        }


        if (!Auth::user()->verified_email) {
            // @todo create an even to send an email message and verify the user
            return redirect()->route('verify.email');
        }

        return redirect()->route('app');
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request) : RedirectResponse
    {
        Auth::logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }
}
