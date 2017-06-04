<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UpdateUserRequest;
use App\Transformers\UserTransformer;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Hash;

class UsersController extends ApiController
{
    public function user()
    {
        return $this->itemResponse($this->user, new UserTransformer);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $this->user;

        $user->first_name    = $request->get('first_name');
        $user->last_name     = $request->get('last_name');
        $user->company       = $request->get('company');
        $user->email         = $request->get('email');
        $user->phone_number  = $request->get('phone_number');
        $user->street_line_1 = $request->get('street_line_1');
        $user->street_line_2 = $request->get('street_line_2');
        $user->city          = $request->get('city');
        $user->state         = $request->get('state');
        $user->country       = $request->get('country');
        $user->zip_code      = $request->get('zip_code');
        $user->bank_details  = $request->get('bank_details');

        $newPassword = $request->get('password');

        if ($newPassword) {
            if (Hash::check($request->get('current_password'), $user->password)) {
                $user->password = $newPassword;
            } else {
                return $this->errorResponse(['Your password is incorrect'], 422);
            }
        }

        $user->save();

        return $this->itemResponse($user, new UserTransformer);
    }

    public function token()
    {
        $jwtAuth = app(AuthManager::class)->guard('api');

        $token = $jwtAuth->fromUser($this->user, 5);

        return $token;
    }

    public function logout()
    {
        $auth = app(AuthManager::class)->guard('web');

        $auth->logout();

        return redirect('login');
    }
}
