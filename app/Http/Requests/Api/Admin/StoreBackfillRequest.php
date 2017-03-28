<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\Request;

class StoreBackfillRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'advertiser'    => 'required',
            'embed'         => 'required',
            'ad_type_id'    => 'required',
            'platform_type' => 'required|in:all,desktop,mobile',
            'width'         => 'required|in:responsive,640,320',
            'ecpm'          => 'numeric',
        ];
    }

    public function transform()
    {
        return [
            'advertiser'    => $this->get('advertiser'),
            'embed'         => $this->get('embed'),
            'ad_type_id'    => $this->get('ad_type_id'),
            'platform_type' => $this->get('platform_type'),
            'width'         => $this->get('width'),
            'ecpm'          => (int) ($this->get('ecpm') * 100),
        ];
    }
}
