<?php

namespace VideoAd\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

abstract class Request extends FormRequest
{
    /**
     * Request constructor.
     */
    public function __construct()
    {
        // @todo should this be here?
//        if(Auth::user() != null) {
//            if (!Auth::user()->verified_phone) {
//                return redirect()->route('verify.phone');
//            }
//
//            if (!Auth::user()->verified_email) {
//                return redirect()->route('verify.email');
//            }
//        }
    }
}
