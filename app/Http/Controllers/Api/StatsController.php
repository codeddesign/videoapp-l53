<?php

namespace VideoAd\Http\Controllers\Api;

use Carbon\Carbon;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Models\CampaignEvent;

class StatsController extends Controller
{
    public function requests()
    {
        $timeRange = Carbon::today()->toDateString();

        return CampaignEvent::whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('name', 'app')
            ->where('event', 'load')
            ->where('created_at', '>=', $timeRange)
            ->count();
    }
}
