<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

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
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'company'    => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|min:6|confirmed',
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
            'first_name'               => $this->get('first_name'),
            'last_name'                => $this->get('last_name'),
            'company'                  => $this->get('company'),
            'email'                    => $this->get('email'),
            'password'                 => $this->get('password'),
            'email_verification_token' => str_random(32),
        ]);
    }
}
