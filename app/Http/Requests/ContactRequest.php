<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
        $validation = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'company' => '',
        ];

        if (!is_null($this->get('message'))) {
            $validation = array_merge($validation, ['message' => 'required']);
        }

        if (!is_null($this->get('website'))) {
            $validation = array_merge($validation, [
                'website' => 'required|url',
                'pageviews' => 'required',
            ]);
        }

        return $validation;
    }
}
