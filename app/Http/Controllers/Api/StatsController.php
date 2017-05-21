<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEvent;
use App\Services\AnalyticsEvents;
use App\Services\CampaignEvents;
use App\Stats\StatsTransformer;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\DB;

class StatsController extends ApiController
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function all(Request $request)
    {
        $timespan = $request->get('time');

        if (! $timespan || $timespan === 'realtime') {
            $stats = $this->fetchRealTimeData();
        } else {
            $stats = $this->fetchHistoricalData($timespan);
        }

        return $this->jsonResponse($stats);
    }

    protected function fetchRealTimeData()
    {
        $campaignIds = $this->user->campaigns->pluck('id')->toArray();
        $websiteIds = $this->user->websites->pluck('id')->toArray();

        $campaignEvents = (new CampaignEvents)->fetchMultipleCampaigns($campaignIds);
        $analyticsEvents = (new AnalyticsEvents)->fetchMultipleWebsites($websiteIds);

        $events = $campaignEvents->merge($analyticsEvents);

        $cache = app(Repository::class);

        /*$databaseEvents = $cache->tags(['events'])->remember("user.{$this->user->id}.events.today}", 30, function () {
            return $this->campaignEvents('today');
        });*/

        $databaseEvents = $this->campaignEvents('today');

        $events = $events->merge($databaseEvents);

        return (new StatsTransformer)->transformSumAll($events, true);
    }

    protected function fetchHistoricalData($timespan)
    {
        $statsByCampaign = $this->campaignEvents($timespan);

        $statsByCampaign = $statsByCampaign
            ->groupBy(function ($item) {
                return $item->created_at->format('F d, Y');
            });

        return (new StatsTransformer)->transform($statsByCampaign, $timespan);;
    }

    protected function campaignEvents($timespan)
    {
        $createdAtTimezone = "((created_at AT TIME ZONE 'UTC') AT TIME ZONE '".$this->user->timezone."')::date";

        $statsByCampaign = CampaignEvent::userStats($timespan)
            ->with('tag', 'backfill')
            ->select('name', 'tag_id', 'backfill_id', DB::raw($createdAtTimezone.' as created_at'), DB::raw('SUM(count) as count'))
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id', 'backfill_id', DB::raw($createdAtTimezone))
            ->get();

        return $statsByCampaign;
    }
}
