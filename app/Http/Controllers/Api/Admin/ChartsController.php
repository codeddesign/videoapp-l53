<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\User;
use App\Stats\StatsTransformer;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $user  = $request->get('user');

        if ($range === 'today' || $range === 'yesterday' || $range === 'lastTwentyFourHours') {
            $step      = CarbonInterval::hour();
            $keyFormat = 'm/d/Y H';
            $createdAtFormat = 'created_at';
        } else {
            $step      = CarbonInterval::day();
            $keyFormat = 'm/d/Y';
            $createdAtFormat = 'created_at::date';
        }

        $stats = CampaignEvent::query()
            ->select('name', 'tag_id', DB::raw($createdAtFormat), DB::raw('SUM(count) as count'))
            ->with('tag', 'website')
            ->where('name', '!=', 'viewership') //viewership data isn't charted
            ->groupBy('name', 'tag_id', DB::raw($createdAtFormat))
            ->timeRange($range);

        if ($user) {
            $websites = User::with('wordpressSites')->find($user)->wordpressSites->pluck('id');
            $stats->whereIn('website_id', $websites);
        }

        //stats contains all events grouped by hour/day
        $stats = $stats->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->created_at->format($keyFormat);
            });

        $transformer = new StatsTransformer;
        $requests    = $transformer->highcharts($stats, $keyFormat, $range, $step, $range === 'lastTwentyFourHours');

        return $this->jsonResponse($requests);
    }
}
