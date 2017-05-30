<?php

namespace App\Http\Controllers\Api;

use App\Transformers\UserTransformer;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;

class UsersController extends ApiController
{
    public function user()
    {
        return $this->itemResponse($this->user, new UserTransformer);
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
