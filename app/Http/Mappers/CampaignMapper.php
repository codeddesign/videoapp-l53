<?php

namespace VideoAd\Http\Mappers;

use VideoAd\Models\Campaign;

/**
 * @author Coded Design
 * Class CampaignMapper
 * @package VideoAd\Http\Mappers
 */
class CampaignMapper
{
    /**
     * @param Campaign $campaign
     * @return array
     */
    public function map(Campaign $campaign)
    {
        return [
            'id' => (int) $campaign->id,
            'name' => $campaign->name,
            'rpm' => (int) $campaign->rpm,
            'size' => $campaign->size,
            'user' => $this->user($campaign),
            'type' => $campaign->type,
            'created_at_humans' => $campaign->created_at_humans
        ];
    }

    /**
     * @param Campaign $campaign
     * @return array
     */
    protected function user(Campaign $campaign)
    {
        return [
            'id' => (int) $campaign->user->id,
            'name' => $campaign->user->name,
            'email' => $campaign->user->email
        ];
    }
}
