<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEvent;
use App\Models\DateRange;
use App\Models\Report;
use App\Models\SessionEvent;
use App\Services\Reports\Query;
use App\Sessions\SessionsCollection;
use App\Stats\StatsTransformer;
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
        $range  = $request->get('time') ?? 'today';
        $report = $request->get('report');

        if ($report) {
            $report    = Report::find($report);
            $dateRange = $report->dateRange();
        } else {
            $dateRange = DateRange::byName($range, $this->user->timezone);
        }

        if ($dateRange->days() > 1) {
            $keyFormat       = 'm/d/Y';
            $createdAtFormat = "((created_at AT TIME ZONE 'UTC') AT TIME ZONE '".$this->user->timezone."')::date";
        } else {
            $keyFormat       = 'm/d/Y H';
            $createdAtFormat = 'created_at';
            //$createdAtFormat = "((created_at AT TIME ZONE 'UTC') AT TIME ZONE '".$this->user->timezone."')";
        }

        $userStats = CampaignEvent::userStats()
            ->select('name', 'tag_id', 'backfill_id', DB::raw($createdAtFormat.' as created_at'), DB::raw('SUM(count) as count'))
            ->with('tag', 'website', 'backfill')
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id', 'backfill_id', DB::raw($createdAtFormat));

        $sessionEvents = SessionEvent::userStats()
            ->select('platform_type', DB::raw($createdAtFormat.' as created_at'), DB::raw('SUM(rpm) as rpm'), DB::raw('SUM(sessions) as sessions'))
            ->timeRange($dateRange)
            ->groupBy('platform_type', DB::raw($createdAtFormat))
            ->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->created_at->format($keyFormat);
            })
            ->map(function ($item) {
                return new SessionsCollection($item);
            });

        if ($report) {
            $userStats = (new Query($report))->filter($userStats);
        } else {
            $userStats = $userStats->timeRange($dateRange);
        }

        $userStats = $userStats->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->created_at->format($keyFormat);
            });

        $calculateTagStats = false;
        if ($range === 'lastTwentyFourHours' || isset($report)) {
            $calculateTagStats = true;
        }

        $stats = (new StatsTransformer)->highcharts($userStats, $keyFormat, $dateRange, $calculateTagStats, $sessionEvents);

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
