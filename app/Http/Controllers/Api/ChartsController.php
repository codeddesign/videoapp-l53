<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEvent;
use App\Models\DateRange;
use App\Stats\StatsTransformer;
use Carbon\Carbon;
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

        $dateRange = DateRange::byName($range);

        if ($dateRange->days() > 1) {
            $keyFormat       = 'm/d/Y';
            $createdAtFormat = "((created_at AT TIME ZONE 'UTC') AT TIME ZONE '".$this->user->timezone."')::date";
        } else {
            $keyFormat       = 'm/d/Y H';
            $createdAtFormat = 'created_at';
        }

        $userStats = CampaignEvent::userStats($range)
            ->select('name', 'tag_id', 'backfill_id', DB::raw($createdAtFormat.' as created_at'), DB::raw('SUM(count) as count'))
            ->with('tag', 'website', 'backfill')
            ->where('name', '!=', 'viewership') //viewership data isn't charted
            ->groupBy('name', 'tag_id', 'backfill_id', DB::raw($createdAtFormat))
            ->with('tag')
            ->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->created_at->format($keyFormat);
            });

        $stats = (new StatsTransformer)->highcharts($userStats, $keyFormat, $range);

        return $stats;
    }

    /**
     * Return an array of the impressions count, ordered by the days of the month.
     *
     * @param \App\Stats\StatsTransformer $statsTransformer
     *
     * @return array
     */
    public function impressions(StatsTransformer $statsTransformer)
    {
        return $statsTransformer->transformToArrayOfImpressions(CampaignEvent::impressionsStats());
    }
}
