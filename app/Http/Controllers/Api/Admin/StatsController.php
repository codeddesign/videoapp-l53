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

        if (! $timespan || $timespan === 'realtime') {
            $stats = $this->fetchRealTimeData($adType);
        } else {
            $stats = $this->fetchHistoricalData($timespan, $tags);
        }

        return $this->jsonResponse($stats);
    }

    protected function fetchRealTimeData($type)
    {
        $campaignIds = null;

        if ($type !== null) {
            $campaignIds = Campaign::with('type')->whereHas('type', function ($query) use ($type) {
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

    protected function fetchHistoricalData($timespan, $tags)
    {
        $statsByCampaign = $this->campaignEvents($timespan, $tags);

        $stats = (new StatsTransformer)->transformSumAll($statsByCampaign, true);

        return $stats;
    }

    protected function campaignEvents($timespan, $tags = null, $campaigns = null)
    {
        $events = CampaignEvent::query()
            ->with('tag')
            ->select('name', 'tag_id', DB::raw('SUM(count) as count'))
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id')
            ->timeRange($timespan, $this->user->timezone);

        if ($tags) {
            $events = $events->whereIn('tag_id', $tags);
        }

        if ($campaigns !== null) {
            $events = $events->whereIn('campaign_id', $campaigns);
        }

        return $events->get();
    }
}
