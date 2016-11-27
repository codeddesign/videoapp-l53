<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
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

        if ($range === 'today' || $range === 'yesterday') {
            $step = CarbonInterval::hour();
            $keyFormat = 'm/d/Y H';
        } else {
            $step = CarbonInterval::day();
            $keyFormat = 'm/d/Y';
        }

        $stats = CampaignEvent::query()
            ->with('tag')
            ->timeRange($range)
            ->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->created_at->format($keyFormat);
            });

        $transformer = new StatsTransformer;
        $requests    = $transformer->highcharts($stats, $keyFormat, $range, $step);

        return $this->jsonResponse($requests);
    }
}
