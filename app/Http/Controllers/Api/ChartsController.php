<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEvent;
use App\Stats\StatsTransformer;
use Carbon\Carbon;
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
        $range = $request->get('time');

        if ($range === 'realtime') {
            $now = Carbon::now()->timestamp * 1000;

            return [
                'requests'    => [[$now, 0]],
                'impressions' => [[$now, 0]],
                'revenue'     => [[$now, 0]],
            ];
        }

        $keyFormat = 'm/d/Y';

        $userStats = CampaignEvent::userStats($range)
            ->with('tag')
            ->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->name.'-'.$item->created_at->format($keyFormat);
            });

        $transformer = new StatsTransformer;
        $requests    = $transformer->transformHighcharts('requests', $userStats, $keyFormat, $range);
        $impressions = $transformer->transformHighcharts('impressions', $userStats, $keyFormat, $range);

        $revenue     = collect($impressions)->map(function ($value) {
            return [$value[0], (4 * $value[1]) / 1000];
        });

        return [
            'requests'    => $requests,
            'impressions' => $impressions,
            'revenue'     => $revenue,
        ];
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
