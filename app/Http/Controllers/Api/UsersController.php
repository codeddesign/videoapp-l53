<?php

namespace App\Http\Controllers\Api;

use App\Transformers\UserTransformer;

class UsersController extends ApiController
{
    public function user()
    {
        return $this->itemResponse($this->user, new UserTransformer);
    }
}
