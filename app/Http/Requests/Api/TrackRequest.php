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
        if ($this->get('tag') === null || $this->get('tag') === 'false') {
            $tag = null;
        } else {
            $tag = $this->get('tag');
        }

        return [
            'campaign' => (int) $this->get('campaign'),
            'source'   => $this->get('source'),
            'status'   => $this->get('status'),
            'tag'      => $tag,
            'referrer' => $this->get('referrer'),
        ];
    }
}
