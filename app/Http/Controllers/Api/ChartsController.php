<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampaignEvent;
use App\Stats\StatsTransformer;

/**
 * @author Coded Design
 */
class ChartsController extends Controller
{
    /**
     * @param StatsTransformer $statsTransformer
     * @return array
     */
    public function stats(StatsTransformer $statsTransformer)
    {
        $requests = $this->requests($statsTransformer);
        $impressions = $this->impressions($statsTransformer);
        $revenue = collect($impressions)->map(function ($value) {
            return (4 * $value) / 1000;
        });

        return [
            'requests' => $requests,
            'impressions' => $impressions,
            'revenue' => $revenue,
        ];
    }

    /**
     * Return an array of the requests count, ordered by the days of the month.
     *
     * @return array
     */
    public function requests(StatsTransformer $statsTransformer)
    {
        return $statsTransformer->transformToArrayOfRequests(CampaignEvent::requestsStats());
    }

    /**
     * Return an array of the impressions count, ordered by the days of the month.
     *
     * @return array
     */
    public function impressions(StatsTransformer $statsTransformer)
    {
        return $statsTransformer->transformToArrayOfImpressions(CampaignEvent::impressionsStats());
    }
}
