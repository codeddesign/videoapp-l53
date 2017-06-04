<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\Request;

class StoreAccountRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'company'    => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
        ];
    }
}
