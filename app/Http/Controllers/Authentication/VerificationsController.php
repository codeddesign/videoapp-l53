<?php

namespace App\Http\Controllers\Authentication;

use App\Services\Nexmo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

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

    public function postVerifyPhone(Request $request)
    {
        $user = app('auth')->user();
        try {
            $response = Nexmo::verifyNumber($request->get('phone'));
            $user->phone_number = $request->get('phone');
            $user->save();

            return redirect()
                ->route('verify.phone')
                ->with(['verify' => 'code']);
        } catch (\Exception $ex) {
            return redirect()
                ->route('verify.phone')
                ->withErrors($ex->getMessage());
        }
    }

    public function postVerifyPhoneCode(Request $request)
    {
        try {
            $response = Nexmo::verifyCode($request->get('phone_code'));

            if ($response->status == 0) {
                app('auth')->user()->confirmPhone();

                return redirect()->route('login');
            }

            return redirect()
                ->route('verify.phone')
                ->with(['verify' => 'code'])
                ->withErrors($response->error_text);
        } catch (\Exception $ex) {
            return redirect()
                ->route('verify.phone')
                ->with(['verify' => 'code'])
                ->withErrors($ex->getMessage());
        }
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

    public function verifyEmailToken($token)
    {
        User::where('email_verification_token', $token)
            ->firstOrFail()
            ->confirmEmail();

        return redirect()
            ->route('login')
            ->with('message', 'Your email is now confirmed. Please login.');
    }
}
