<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\DateRange;
use App\Models\Report;
use App\Models\User;
use App\Stats\StatsTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $user   = $request->get('user');
        $report = $request->get('report');
        $tags   = $request->get('tags') ? explode(',', $request->get('tags')) : null;

        if ($report) {
            $report    = Report::find($report);
            $dateRange = $report->dateRange();
        } else {
            $dateRange = DateRange::byName($range, $this->user->timezone);
        }

        if ($dateRange->days() > 1) {

            $keyFormat       = 'm/d/Y';
            $createdAtFormat = "((created_at AT TIME ZONE 'UTC') AT TIME ZONE '". $this->user->timezone . "')::date";
        } else {
            $keyFormat       = 'm/d/Y H';
            $createdAtFormat = 'created_at';
        }

        $stats = CampaignEvent::query()
            ->select('name', 'tag_id', DB::raw($createdAtFormat. ' as created_at'), DB::raw('SUM(count) as count'))
            ->with('tag', 'website')
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id', DB::raw($createdAtFormat));

        if ($tags) {
            $stats = $stats->whereIn('tag_id', $tags);
        }

        if ($report) {
            $stats = $report->filterQuery($stats);
        } else {
            $stats = $stats->timeRange($dateRange);
        }

        if ($user) {
            $websites = User::with('websites')->find($user)->websites->pluck('id');
            $stats->whereIn('website_id', $websites);
        }

        //stats contains all events grouped by hour/day
        $stats = $stats->get()
            ->groupBy(function ($item) use ($keyFormat) {
                return $item->created_at->format($keyFormat);
            });


        $calculateTagStats = false;
        if ($range === 'lastTwentyFourHours' || isset($report)) {
            $calculateTagStats = true;
        }

        $transformer = new StatsTransformer;
        $requests    = $transformer->highcharts($stats, $keyFormat, $dateRange, $calculateTagStats);

        return $this->jsonResponse($requests);
    }
}
