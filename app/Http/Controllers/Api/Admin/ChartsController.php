<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\User;
use App\Stats\StatsTransformer;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class ChartsController extends ApiController
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function stats(Request $request)
    {
        $range = $request->get('time') ?? 'today';
        $user = $request->get('user');

        if ($range === 'today' || $range === 'yesterday' || $range === 'lastTwentyFourHours') {
            $step = CarbonInterval::hour();
            $keyFormat = 'm/d/Y H';
        } else {
            $step = CarbonInterval::day();
            $keyFormat = 'm/d/Y';
        }

        $stats = CampaignEvent::query()
            ->with('tag', 'website')
            ->timeRange($range);

        if ($user) {
            $websites = User::with('wordpressSites')->find($user)->wordpressSites->pluck('id');
            $stats->whereIn('website_id', $websites);
        }

        $stats = $stats->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->created_at->format($keyFormat);
            });

        $transformer = new StatsTransformer;
        $requests    = $transformer->highcharts($stats, $keyFormat, $range, $step);

        return $this->jsonResponse($requests);
    }
}
