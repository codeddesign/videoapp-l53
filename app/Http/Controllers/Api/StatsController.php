<?php

namespace VideoAd\Http\Controllers\Api;

use VideoAd\Models\CampaignEvent;
use VideoAd\Http\Controllers\Controller;

/**
 * @author Coded Design
 * Class StatsController
 * @package VideoAd\Http\Controllers\Api
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
        // time can be equal to: 'today', '7-days', 'current-month', 'last-month'
        return CampaignEvent::whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('name', 'app')->where('event', 'load')->timeRange(request())->count();
    }

    /**
     * Count the impressions per date range.
     * 
     * @return integer
     */
    public function impressions()
    {
        // we are expecting to fetch a 'time' value for the request.
        // time can be equal to: 'today', '7-days', 'current-month', 'last-month'
        return CampaignEvent::whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('name', 'ad')->where('event', 'start')->timeRange(request())->count();
    }
}
