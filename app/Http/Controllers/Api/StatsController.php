<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEvent;
use App\Services\AnalyticsEvents;
use App\Services\CampaignEvents;
use App\Sessions\DatabaseSessions;
use App\Sessions\RedisSessions;
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
        $tags     = $request->get('tags') ? explode(',', $request->get('tags')) : null;
        $website  = ($request->get('website') != 0) ? $request->get('website') : null;

        if (! $timespan || $timespan === 'realtime') {
            $stats = $this->fetchRealTimeData();
        } else {
            $stats = $this->fetchHistoricalData($timespan, $tags, $website);
        }

        return $this->jsonResponse($stats);
    }

    protected function fetchRealTimeData()
    {
        $campaignIds = $this->user->campaigns->pluck('id')->toArray();
        $websiteIds  = $this->user->websites->pluck('id')->toArray();

        $campaignEvents  = (new CampaignEvents)->fetchMultipleCampaigns($campaignIds);
        $analyticsEvents = (new AnalyticsEvents)->fetchMultipleWebsites($websiteIds);

        $events = $campaignEvents->merge($analyticsEvents);

        $cache = app(Repository::class);

        /*$databaseEvents = $cache->tags(['events'])->remember("user.{$this->user->id}.events.today}", 30, function () {
            return $this->campaignEvents('today');
        });*/

        $databaseEvents = $this->campaignEvents('today');

        $events = $events->merge($databaseEvents);

        $sessions = (new RedisSessions)->fetch()->merge(
            (new DatabaseSessions)->fetch('today', $this->user->timezone)
        );

        return array_merge((new StatsTransformer)->transformSumAll($events, true), [
            'desktopRpm' => $sessions->rpm('desktop'),
            'mobileRpm' => $sessions->rpm('mobile'),
        ]);
    }

    protected function fetchHistoricalData($timespan, $tags, $website)
    {
        $statsByCampaign = $this->campaignEvents($timespan, $tags, $website);

        $statsByCampaign = $statsByCampaign
            ->groupBy(function ($item) {
                return $item->created_at->format('F d, Y');
            });

        return (new StatsTransformer)->transform($statsByCampaign, $timespan);
    }

    protected function campaignEvents($timespan, $tags = null, $website = null)
    {
        $createdAtTimezone = "((created_at AT TIME ZONE 'UTC') AT TIME ZONE '".$this->user->timezone."')::date";

        $statsByCampaign = CampaignEvent::userStats()
            ->with('tag', 'backfill')
            ->select('name', 'tag_id', 'backfill_id', DB::raw($createdAtTimezone.' as created_at'), DB::raw('SUM(count) as count'))
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id', 'backfill_id', DB::raw($createdAtTimezone))
            ->timeRange($timespan, $this->user->timezone);

        if ($tags) {
            $statsByCampaign = $statsByCampaign->whereIn('tag_id', $tags);
        }

        if ($website) {
            $statsByCampaign = $statsByCampaign->where('website_id', $website);
        }

        return $statsByCampaign->get();
    }
}
