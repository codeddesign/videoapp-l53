<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TrackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function transform()
    {
        return [
            'source'   => $this->get('source'),
            'status'   => (int) $this->get('status'),
            'tag'      => $this->get('tag'),
            'campaign' => (int) $this->get('campaign'),
        ];
    }
}
