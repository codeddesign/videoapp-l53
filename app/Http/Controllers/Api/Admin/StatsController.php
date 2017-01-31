<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Services\AnalyticsEvents;
use App\Services\CampaignEvents;
use App\Stats\StatsTransformer;
use Illuminate\Http\Request;
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
        $campaignEvents  = (new CampaignEvents)->fetchAllCampaigns();
        $analyticsEvents = (new AnalyticsEvents)->fetchAllAnalytics();

        $events = $campaignEvents->merge($analyticsEvents);
        $events = $events->merge($this->campaignEvents('today'));

        return (new StatsTransformer)->transformSumAll($events, true);
    }

    protected function fetchHistoricalData($timespan)
    {
        $statsByCampaign = $this->campaignEvents($timespan);

        $stats = (new StatsTransformer)->transformSumAll($statsByCampaign, true);

        return $stats;
    }

    protected function campaignEvents($timespan)
    {
        return CampaignEvent::query()
            ->with('tag')
            ->select('name', 'tag_id', DB::raw('SUM(count) as count'))
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id')
            ->timeRange($timespan, $this->user->timezone)
            ->get();
    }
}
