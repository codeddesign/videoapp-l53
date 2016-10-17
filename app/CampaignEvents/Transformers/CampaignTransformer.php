<?php

namespace App\CampaignEvents\Transformers;

use App\Models\Campaign;

class CampaignTransformer
{
    /**
     * Transform the campaign data into the desired form.
     *
     * @param $campaign
     * @return array
     */
    public static function transform(Campaign $campaign)
    {
        return [
            'campaign' => filterModelKeys(
                $campaign->toArray(),
                ['id', 'name', 'size', 'url', 'source']
            ),
            'info' => [
                'type' => $campaign->type->alias,
                'available' => $campaign->type->available,
                'single' => $campaign->type->single,
                'has_name' => $campaign->type->has_name,
            ],
            'tags' => env_adTags(),
        ];
    }
}
