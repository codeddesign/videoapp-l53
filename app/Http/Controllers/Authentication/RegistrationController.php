<?php

namespace App\Http\Controllers\Authentication;

use App\Events\AccountCreated;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{
    /**
     * Show the registration page.
     *
     * @return View
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register the user.
     *
     * @param RegistrationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegistrationRequest $request): RedirectResponse
    {
        // Register the user if the data is valid.
        // the 'register' method is added in the 'RegistrationRequest'
        // form request, just to add more clarity.
        $user = $request->register();

        $defaultReports = $this->defaultReports($user);
        Report::saveMany($defaultReports);

        // send verification email and phone.
        event(new AccountCreated($user));

        // login the created user.
        Auth::login($user);
        $token = Auth::guard('api')->fromUser($user);

        $jwtCookie = cookie('jwt_token', $token, 0, null, null, false, false);

        // login the user and redirect to the phone verification page.
        return redirect()->route('verify.phone')->withCookie($jwtCookie);
    }

    protected function defaultReports(User $user)
    {
        $reports = new Collection([
            [
                'title'      => 'Today',
                'date_range' => 'today',
            ],
            [
                'title'      => 'Yesterday',
                'date_range' => 'yesterday',
            ],
            [
                'title'      => 'Last 3 Days',
                'date_range' => 'lastThreeDays',
            ],
            [
                'title'      => 'Last 7 Days',
                'date_range' => 'lastSevenDays',
            ],
            [
                'title'      => 'Last 30 Days',
                'date_range' => 'lastThirtyDays',
            ],
            [
                'title'      => 'This Month',
                'date_range' => 'thisMonth',
            ],
            [
                'title'      => 'Last Month',
                'date_range' => 'lastMonth',
            ],
        ]);

        $reports = $reports->map(function ($report) use ($user) {
            return array_merge($report, [
                'user_id'          => $user->id,
                'recipient'        => $user->email,
                'sort_by'          => 'advertiser',
                'schedule'         => 'once',
                'deletable'        => false,
                'included_metrics' => json_encode(Report::allMetrics()),
                'filter'           => json_encode([
                    'type'   => '',
                    'value'  => '',
                    'filter' => '',
                ]),
            ]);
        });

        return $reports;
    }
}
