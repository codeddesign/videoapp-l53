<?php

namespace VideoAd\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
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
    public function test()
    {
        return collect([1, 2, 3, 4, 5, 5, 4, 1, 7, 6, 4, 9, 6, 4, 2, 1, 2, 3, 4, 5, 5, 4, 8, 9, 0, 0, 0, 0, 0, 0]);
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
