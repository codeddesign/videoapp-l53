<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\Services\AnalyticsEvents;
use App\Services\CampaignEvents;
use App\Stats\StatsTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Cache\Repository;

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
        $adType   = $request->get('type');
        $tags     = $request->get('tags') ? explode(',', $request->get('tags')) : null;
        $website  = ($request->get('website') != 0) ? $request->get('website') : null;
        $backfillFilter = $request->get('backfill') ? explode(',', $request->get('backfill')) : null;

        if (! $timespan || $timespan === 'realtime') {
            $stats = $this->fetchRealTimeData($adType);
        } else {
            $stats = $this->fetchHistoricalData($timespan, $tags, $backfillFilter, $website);
        }

        return $this->jsonResponse($stats);
    }

    protected function fetchRealTimeData($type)
    {
        $campaignIds = null;

        if ($type !== null) {
            $campaignIds = Campaign::with('type')
                ->whereHas('type', function ($query) use ($type) {
                    $query->where('ad_type_id', $type);
                })->get()->pluck('id')->toArray();

            $campaignEvents  = (new CampaignEvents)->fetchMultipleCampaigns($campaignIds);
            $analyticsEvents = (new AnalyticsEvents)->fetchAllAnalytics();
        } else {
            $campaignEvents  = (new CampaignEvents)->fetchAllCampaigns();
            $analyticsEvents = (new AnalyticsEvents)->fetchAllAnalytics();
        }

        $events = $campaignEvents->merge($analyticsEvents);

        $cache = app(Repository::class);

        $databaseEvents = $cache->tags(['events'])->remember("events.today.{$type}", 30, function () use ($campaignIds) {
            return $this->campaignEvents('today', null, $campaignIds);
        });

        $events = $events->merge($databaseEvents);

        return (new StatsTransformer)->transformSumAll($events, true);
    }

    protected function fetchHistoricalData($timespan, $tags, $backfillFilter, $website)
    {
        $statsByCampaign = $this->campaignEvents($timespan, $tags, null, $backfillFilter, $website);

        $stats = (new StatsTransformer)->transformSumAll($statsByCampaign, true);

        return $stats;
    }

    protected function campaignEvents($timespan, $tags = null, $campaigns = null, $backfillFilter = null, $website = null)
    {
        $events = CampaignEvent::query()
            ->with('tag', 'backfill')
            ->select('name', 'tag_id', 'backfill_id', DB::raw('SUM(count) as count'))
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id', 'backfill_id')
            ->timeRange($timespan, $this->user->timezone);

        if ($tags) {
            $events = $events->whereIn('tag_id', $tags);
        }

        if ($website) {
            $events = $events->where('website_id', $website);
        }

        if ($backfillFilter) {
            $events = $events->whereIn('backfill_id', $backfillFilter);
        }

        if ($campaigns !== null) {
            $events = $events->whereIn('campaign_id', $campaigns);
        }

        return $events->get();
    }
}
