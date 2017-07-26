<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email'      => [
                'required',
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
            'password'   => 'confirmed',
        ];
    }
}
