<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\Request;
use Carbon\Carbon;

class StoreTagRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'url'                     => 'required',
            'advertiser'              => 'required',
            'description'             => 'required',
            'platform_type'           => 'required|in:all,desktop,mobile',
            'campaign_types.infinity' => 'boolean',
            'campaign_types.onscroll' => 'boolean',
            'campaign_types.preroll'  => 'boolean',
            'campaign_types.unknown'  => 'boolean',
            'ad_type'                 => 'required|in:all,instream,outstream',
            'date_range'              => 'boolean',
            'start_date'              => 'nullable|date',
            'end_date'                => 'nullable|date|after:start_date',
            'daily_request_limit'     => 'numeric',
            'delay_time'              => 'numeric',
            'ecpm'                    => 'numeric',
            'guarantee_limit'         => 'numeric',
            'guarantee_order'         => 'numeric',
            'priority_count'          => 'numeric',
            'timeout_limit'           => 'numeric',
            'wrapper_limit'           => 'numeric',
        ];
    }

    public function transform()
    {
        return [
            'url'                 => $this->get('url'),
            'advertiser'          => $this->get('advertiser'),
            'description'         => $this->get('description'),
            'platform_type'       => $this->get('platform_type'),
            'campaign_types'      => array_keys(array_filter($this->get('campaign_types'))),
            'ad_type'             => $this->get('ad_type'),
            'date_range'          => (boolean) $this->get('date_range'),
            'start_date'          => $this->get('start_date') ? Carbon::parse($this->get('start_date')) : null,
            'end_date'            => $this->get('end_date') ? Carbon::parse($this->get('end_date')) : null,
            'daily_request_limit' => (int) $this->get('daily_request_limit'),
            'delay_time'          => (int) $this->get('delay_time'),
            'ecpm'                => (int) $this->get('ecpm'),
            'guarantee_limit'     => (int) $this->get('guarantee_limit'),
            'guarantee_order'     => (int) $this->get('guarantee_order'),
            'guarantee_enabled'   => (boolean) $this->get('guarantee_enabled'),
            'priority_count'      => (int) $this->get('priority_count'),
            'timeout_limit'       => (int) $this->get('timeout_limit'),
            'wrapper_limit'       => (int) $this->get('wrapper_limit'),
            'included_locations'  => $this->flattenLocations($this->get('included_locations')),
            'excluded_locations'  => $this->flattenLocations($this->get('excluded_locations')),
        ];
    }

    private function flattenLocations($locations)
    {
        $locations = collect($locations);

        $locations = $locations->map(function ($location) {
            $locationArray = [$location['type'] => $location['name']];

            if (array_get($location, 'parent') === null) {
                return $locationArray;
            }

            return array_merge(
                $location['parent'],
                $locationArray
            );
        });

        return $locations;
    }
}
