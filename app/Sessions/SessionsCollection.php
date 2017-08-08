<?php

namespace App\Sessions;

use Illuminate\Support\Collection;

class SessionsCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public function rpm($platform = null)
    {
        $oldItems = $this->items;

        if ($platform != null) {
            $this->items = $this->filter(function ($item) use ($platform) {
                return $item['platform_type'] === $platform;
            })->all();
        }

        $sessionsSum = $this->sum('sessions');
        $rpmSum = $this->sum('rpm');

        $this->items = $oldItems;

        if ($sessionsSum === 0) {
            return 0;
        }

        return $rpmSum / 100 / $sessionsSum;
    }

    public function filterByCampaignIds($campaignIds)
    {
        $this->items = $this->filter(function ($item) use ($campaignIds) {
            return in_array($item['campaign_id'], $campaignIds);
        })->all();
    }
}
