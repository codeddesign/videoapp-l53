<?php

namespace App\Transformers;

use App\Models\Tag;
use App\Models\Website;

class TagTransformer extends Transformer
{
    protected $websites;

    public function __construct()
    {
        $this->websites = Website::all();
    }

    public function transform(Tag $tag)
    {
        $transformedTag = [
            'id'                     => (int) $tag->id,
            'url'                    => $tag->url,
            'advertiser'             => $tag->advertiser,
            'description'            => $tag->description,
            'platform_type'          => $tag->platform_type,
            'ad_types'               => $tag->ad_types,
            'type'                   => $tag->type,
            'date_range'             => (boolean) $tag->date_range,
            'for_owned'              => (boolean) $tag->for_owned,
            'start_date'             => $this->date($tag->start_date),
            'end_date'               => $this->date($tag->end_date),
            'daily_request_limit'    => (int) $tag->daily_request_limit,
            'ecpm'                   => (float) $tag->ecpm / 100,
            'guarantee_limit'        => (int) $tag->guarantee_limit,
            'guarantee_order'        => (int) $tag->guarantee_order,
            'guarantee_enabled'      => (boolean) $tag->guarantee_enabled,
            'priority_count'         => (int) $tag->priority_count,
            'timeout_limit'          => (int) $tag->timeout_limit,
            'wrapper_limit'          => (int) $tag->wrapper_limit,
            'delay_time'             => (int) $tag->delay_time,
            'infinity_timeout_limit' => (int) $tag->infinity_timeout_limit,
            'infinity_wrapper_limit' => (int) $tag->infinity_wrapper_limit,
            'infinity_delay_time'    => (int) $tag->infinity_delay_time,
            'included_websites'      => $this->parseWebsites($tag->included_websites),
            'excluded_websites'      => $this->parseWebsites($tag->excluded_websites),
            'included_locations'     => $this->parseLocations($tag->included_locations),
            'excluded_locations'     => $this->parseLocations($tag->excluded_locations),
            'active'                 => (boolean) $tag->active,
            'demo_data'              => $tag->demo_data,
            'user_id'                => $tag->user_id,
        ];

        if ($tag->stats) {
            $transformedTag = array_merge($transformedTag, [
                'stats' => $tag->stats,
            ]);
        }

        return $transformedTag;
    }

    protected function parseWebsites($websites)
    {
        $websites = $this->websites->whereIn('id', $websites);

        $transformedWebsites = [];
        $websiteTransformer  = new WebsiteTransformer;

        foreach ($websites as $website) {
            $transformedWebsites[] = $websiteTransformer->transform($website);
        }

        return $transformedWebsites;
    }

    protected function parseLocations($locations)
    {
        $locations = collect($locations)->map(function ($location) {
            if (isset($location['city'])) {
                return [
                    'name'   => $location['city'],
                    'type'   => 'city',
                    'parent' => [
                        'country' => $location['country'],
                        'state'   => $location['state'],
                    ],
                ];
            } elseif (isset($location['state'])) {
                return [
                    'name'   => $location['state'],
                    'type'   => 'state',
                    'parent' => [
                        'country' => $location['country'],
                    ],
                ];
            } else {
                return [
                    'name' => $location['country'],
                    'type' => 'country',
                ];
            }
        });

        return $locations;
    }
}
