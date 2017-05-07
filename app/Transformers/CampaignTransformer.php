<?php

namespace App\Transformers;

use App\Models\Campaign;

class CampaignTransformer extends Transformer
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
        '30dayrevenue',
    ];

    public function transform(Campaign $campaign)
    {
        $transformedCampaign = [
            'id'                => (int) $campaign->id,
            'name'              => $campaign->name,
            'rpm'               => (int) $campaign->rpm,
            'size'              => $campaign->size,
            'type'              => $campaign->type,
            'ad_type'           => $campaign->type->adType->id,
            'ad_type_name'      => $campaign->type->adType->name,
            'active'            => (boolean) $campaign->active,
            'embed'             => $campaign->embedCode(),
            'created_at_humans' => $campaign->created_at_humans,
        ];

        if ($campaign->stats) {
            $transformedCampaign = array_merge($transformedCampaign, [
                'stats' => $campaign->stats,
            ]);
        }

        return $transformedCampaign;
    }

    /**
     * Include User
     *
     * @param \App\Models\Campaign $campaign
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Campaign $campaign)
    {
        $user = $campaign->user;

        return $this->item($user, new UserTransformer);
    }

    public function include30DayRevenue(Campaign $campaign)
    {
        $user = $campaign->user;

        return $this->item($user, new UserTransformer);
    }
}
