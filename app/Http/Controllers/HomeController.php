<?php

namespace App\Http\Controllers;

use App\Mail\BetaSignup;
use App\Models\Signup;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the public homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('home.index');
    }

    public function signup(Request $request)
    {
        $signup = Signup::create($request->only([
            'name',
            'email',
            'phone',
            'website',
        ]));

        $mailer = app(Mailer::class);

        $mailer->to(['john@ad3media.com','daniel@ad3media.com'])->send(new BetaSignup($signup));

        return redirect('/#betasignup')->with('status', 'Signed Up');
    }
}
