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
        return [
            'id'                => (int) $campaign->id,
            'name'              => $campaign->name,
            'rpm'               => (int) $campaign->rpm,
            'size'              => $campaign->size,
            'type'              => $campaign->type,
            'created_at_humans' => $campaign->created_at_humans,
        ];
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
