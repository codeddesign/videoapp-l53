<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;

class AccountsController extends ApiController
{
    public function index()
    {
        $accounts = User::with('campaigns')->get();

        return $this->collectionResponse($accounts, new UserTransformer);
    }

    public function show($id)
    {
        $account = User::where('id', $id)->with('wordpressSites');

        return $account;
    }

    public function activate($id, Request $request)
    {
        $user = User::findOrFail($id);

        $user->active = $request->get('status');
        $user->save();

        return $this->itemResponse($user, new UserTransformer);
    }
}
