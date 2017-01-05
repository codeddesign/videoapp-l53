<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Services\CampaignEvents;
use App\Stats\StatsTransformer;
use Illuminate\Http\Request;

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
        $stats = (new CampaignEvents)->fetchAllCampaigns(true);

        return (new StatsTransformer)->transformSumAll($stats, true);
    }

    protected function fetchHistoricalData($timespan)
    {
        $statsByCampaign = CampaignEvent::query()
            ->timeRange($timespan)
            ->with('tag')
            ->get();

        $stats = (new StatsTransformer)->transformSumAll($statsByCampaign);

        return $stats;
    }
}
