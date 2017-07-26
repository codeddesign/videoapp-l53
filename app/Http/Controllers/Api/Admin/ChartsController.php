<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\DateRange;
use App\Models\Report;
use App\Models\Campaign;
use App\Models\User;
use App\Services\Reports\Query;
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
        $range          = $request->get('time') ?? 'today';
        $user           = $request->get('user');
        $report         = $request->get('report');
        $adType         = $request->get('type');
        $tags           = $request->get('tags') ? explode(',', $request->get('tags')) : null;
        $website        = ($request->get('website') != 0) ? $request->get('website') : null;
        $backfillFilter = $request->get('backfill') ? explode(',', $request->get('backfill')) : null;

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
        }

        $stats = CampaignEvent::query()
            ->select('name', 'tag_id', 'backfill_id', DB::raw($createdAtFormat.' as created_at'), DB::raw('SUM(count) as count'))
            ->with('tag', 'website', 'backfill')
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id', 'backfill_id', DB::raw($createdAtFormat));

        if ($tags) {
            $stats = $stats->whereIn('tag_id', $tags);
        }

        if ($backfillFilter) {
            $stats = $stats->whereIn('backfill_id', $backfillFilter);
        }

        if ($adType) {
            $campaignIds = Campaign::with('type')->whereHas('type', function ($query) use ($adType) {
                $query->where('ad_type_id', $adType);
            })->get()->pluck('id')->toArray();

            $stats = $stats->whereIn('campaign_id', $campaignIds);
        }

        if ($website) {
            $stats = $stats->where('website_id', $website);
        }

        if ($report) {
            $stats = (new Query($report))->filter($stats);
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
