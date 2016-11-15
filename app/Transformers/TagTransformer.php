<?php

namespace App\Transformers;

use App\Models\Tag;

class TagTransformer extends Transformer
{
    public function transform(Tag $tag)
    {
        return [
            'id'                  => (int) $tag->id,
            'url'                 => $tag->url,
            'advertiser'          => $tag->advertiser,
            'description'         => $tag->description,
            'platform_type'       => $tag->platform_type,
            'campaign_types'      => $this->fillCampaignTypes($tag->campaign_types),
            'ad_type'             => $tag->ad_type,
            'date_range'          => (boolean) $tag->date_range,
            'start_date'          => $this->date($tag->start_date),
            'end_date'            => $this->date($tag->end_date),
            'daily_request_limit' => (int) $tag->daily_request_limit,
            'delay_time'          => (int) $tag->delay_time,
            'ecpm'                => (int) $tag->ecpm,
            'guarantee_limit'     => (int) $tag->guarantee_limit,
            'guarantee_order'     => (int) $tag->guarantee_order,
            'guarantee_enabled'   => (boolean) $tag->guarantee_order,
            'priority_count'      => (int) $tag->priority_count,
            'timeout_limit'       => (int) $tag->timeout_limit,
            'wrapper_limit'       => (int) $tag->wrapper_limit,
            'active'              => (boolean) $tag->active,
        ];
    }

    protected function fillCampaignTypes($types)
    {
        $allTypes = collect([
            'preroll'  => false,
            'onscroll' => false,
            'infinity' => false,
            'unknown'  => false,
        ]);

        return $allTypes->map(function ($value, $type) use ($types) {
            if (in_array($type, $types)) {
                return true;
            }

            return false;
        });
    }
}
