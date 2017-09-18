<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\SignupRequest;
use App\Mail\BetaSignup;
use App\Mail\Contact;
use App\Models\Signup;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Redis\RedisManager;
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

        $mailer->to(['ian@ad3media.com', 'daniel@ad3media.com', 'bryant@ad3media.com'])->send(new BetaSignup($signup));

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
            'website',
            'pageviews',
        ]);

        app(Mailer::class)->send(new Contact($details));

        return ['success' => true];
    }

    public function getCompany()
    {
        $current = 0; // $this->getCompanyLive();

        return view('home.company', compact('current'));
    }

    public function getCacheq()
    {
        return view('home.cacheq');
    }

    public function getAdServing()
    {
        return view('home.adserving');
    }

    /**
     * @return int
     */
    public function getCompanyLive()
    {
        $redis = app(RedisManager::class);
        $current = 0;
        foreach ($redis->hgetall('daily_tag_requests') as $no) {
            $current += (int) $no ?? 0;
        }

        // remove 25%
        $aproximated = round($current - .25 * $current);

        // minimum: 100.000
        if ($aproximated < 1e5) {
            return 1e5;
        }

        return $aproximated;
    }

    public function demo($mode = 'in-article')
    {
        $details = [
            'in-article' => [
                'title' => 'In-Article',
                'campaign' => 1,
            ],
            'sidebar' => [
                'title' => 'Sidebar',
                'campaign' => 2,
            ],
            'display-plus' => [
                'title' => 'Display Plus',
                'campaign' => 3,
            ],
        ];

        if (!isset($details[$mode])) {
            $mode = 'in-article';
        }

        $info = $details[$mode];

        return view('home.demo', compact('info'));
    }
}
