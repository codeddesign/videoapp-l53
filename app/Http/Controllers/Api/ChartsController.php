<?php

namespace VideoAd\Http\Controllers\Api;

use VideoAd\Http\Controllers\Controller;
use VideoAd\Models\CampaignEvent;
use VideoAd\Stats\StatsTransformer;

/**
 * @author Coded Design
 * Class ChartsController
 * @package VideoAd\Http\Controllers\Api
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
            'revenue' => $revenue
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
