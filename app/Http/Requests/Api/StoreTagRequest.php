<?php

namespace App\Http\Requests\Api;

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
            'url'                    => 'required',
            'advertiser'             => 'required',
            'description'            => 'required',
            'platform_type'          => 'required|in:all,desktop,mobile',
            'ad_types'               => 'required',
            'type'                   => 'required|in:all,instream,outstream',
            'date_range'             => 'boolean',
            'start_date'             => 'nullable|date',
            'end_date'               => 'nullable|date|after:start_date',
            'daily_request_limit'    => 'numeric',
            'for_owned'              => 'boolean',
            'ecpm'                   => 'numeric',
            'guarantee_limit'        => 'numeric',
            'guarantee_order'        => 'numeric',
            'timeout_limit'          => 'numeric',
            'wrapper_limit'          => 'numeric',
            'delay_time'             => 'numeric',
            'infinity_timeout_limit' => 'numeric',
            'infinity_wrapper_limit' => 'numeric',
            'infinity_delay_time'    => 'numeric',
        ];
    }

    public function transform($user)
    {
        return [
            'url'                    => $this->get('url'),
            'advertiser'             => $this->get('advertiser'),
            'description'            => $user . '_' . $this->get('description'),
            'platform_type'          => $this->get('platform_type'),
            'ad_types'               => $this->get('ad_types'),
            'type'                   => $this->get('type'),
            'date_range'             => (boolean) $this->get('date_range'),
            'start_date'             => $this->get('start_date') ? Carbon::parse($this->get('start_date')) : null,
            'end_date'               => $this->get('end_date') ? Carbon::parse($this->get('end_date')) : null,
            'daily_request_limit'    => (int) $this->get('daily_request_limit'),
            'ecpm'                   => (int) ($this->get('ecpm') * 100),
            'guarantee_limit'        => (int) $this->get('guarantee_limit'),
            'guarantee_order'        => (int) $this->get('guarantee_order'),
            'guarantee_enabled'      => (boolean) $this->get('guarantee_enabled'),
            'for_owned'              => (boolean) $this->get('for_owned'),
            'timeout_limit'          => (int) $this->get('timeout_limit'),
            'wrapper_limit'          => (int) $this->get('wrapper_limit'),
            'delay_time'             => (int) $this->get('delay_time'),
            'infinity_timeout_limit' => (int) $this->get('infinity_timeout_limit'),
            'infinity_wrapper_limit' => (int) $this->get('infinity_wrapper_limit'),
            'infinity_delay_time'    => (int) $this->get('infinity_delay_time'),
            'included_websites'      => $this->parseWebsites($this->get('included_websites')),
            'excluded_websites'      => $this->parseWebsites($this->get('excluded_websites')),
            'included_locations'     => $this->flattenLocations($this->get('included_locations')),
            'excluded_locations'     => $this->flattenLocations($this->get('excluded_locations')),
            'demo_data'              => $this->get('demo_data'),
            'priority_count'         => 1,
        ];
    }

    private function parseWebsites($websites)
    {
        if ($websites === null) {
            return [];
        }

        $websiteIds = [];

        foreach ($websites as $website) {
            $websiteIds[] = $website['id'];
        }

        return $websiteIds;
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
