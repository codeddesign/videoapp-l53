<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\SignupRequest;
use App\Mail\BetaSignup;
use App\Mail\Contact;
use App\Models\Signup;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\URL;
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

    public function signup(SignupRequest $request)
    {
        $signup = Signup::create($request->only([
            'name',
            'email',
            'phone',
            'website',
        ]));

        $mailer = app(Mailer::class);

        $mailer->to(['john@ad3media.com', 'daniel@ad3media.com', 'bryant@ad3media.com'])->send(new BetaSignup($signup));

        return redirect(URL::previous().'#betasignup')->with('status', 'Signed Up');
    }

    public function getContact()
    {
        return view('home.contact');
    }

    public function postContact(ContactRequest $request)
    {
        $details = $request->only([
            'name',
            'email',
            'phone',
            'company',
            'message',
        ]);

        app(Mailer::class)
            ->to(['john@ad3media.com', 'daniel@ad3media.com', 'bryant@ad3media.com'])
            ->send(new Contact($details));

        return redirect('/contact')->with('status', 'Done');
    }

    public function getFeatures()
    {
        return view('home.features');
    }
}
