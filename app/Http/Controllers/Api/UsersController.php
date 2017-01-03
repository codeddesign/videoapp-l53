<?php

namespace App\Http\Controllers\Api;

use App\Transformers\UserTransformer;
use Illuminate\Auth\AuthManager;

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
}
