<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\Http\Controllers\Controller;

/**
 * @author Coded Design
 */
class StatsController extends Controller
{
    /**
     * Count the requests per date range.
     *
     * @return int
     */
    public function requests()
    {
        // we are expecting to fetch a 'time' value for the request.
        // 'time' can be equal to: 'today', '7-days', 'current-month', 'last-month'
        return CampaignEvent::requests(request())->count();
    }

    /**
     * Count the impressions per date range.
     *
     * @return int
     */
    public function impressions()
    {
        // we are expecting to fetch a 'time' value for the request.
        // 'time' can be equal to: 'today', 'yesterday', '7-days', 'current-month', 'last-month'
        return CampaignEvent::impressions(request())->count();
    }

    public function latestCampaigns()
    {
        // date (format: july 10, 2016), requests, fill-rate, eCPM, revenue
        // paginated
        // collection methods to consider: reduce
        $events = Campaign::find(9)->campaignEvents()->orderBy('created_at')->get();

        return $events;
        $events->transform(function ($item, $key) {
        });
    }
}