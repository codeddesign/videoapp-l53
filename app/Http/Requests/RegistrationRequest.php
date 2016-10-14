<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    }

    /**
     * Register the user.
     *
     * @return User
     */
    public function register()
    {
        return User::create([
            'name'                     => $this->get('name'),
            'email'                    => $this->get('email'),
            'password'                 => $this->get('password'),
            'email_verification_token' => str_random(32),
        ]);
    }
}
