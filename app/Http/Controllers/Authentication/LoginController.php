<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Auth\AuthManager;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    protected $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth->guard('api');
    }

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
     *
     * @return RedirectResponse
     */
    public function login(LoginRequest $request) : RedirectResponse
    {
        $token = $this->auth->attempt([
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        if (! $token) {
            return redirect()->back()->withInput()->withErrors([
                $request->get('email') => 'Credentials do not match our records',
            ]);
        }

        $jwtCookie = cookie('jwt_token', $token, 0, null, null, false, false);

        if (! $this->auth->user()->verified_phone) {
            return redirect()->route('verify.phone')->withCookie($jwtCookie);
        }

        return redirect()->route('app')->withCookie($jwtCookie);
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request $request
     *
     * @return RedirectResponse
     */
    public function logout(Request $request) : RedirectResponse
    {
        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }
}
