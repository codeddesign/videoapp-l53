<?php

namespace App\Http\Controllers\Api;

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
        return (new CampaignEvents)->fetchUserCampaignsFromRedis($this->user);
    }

    protected function fetchHistoricalData($timespan)
    {
        $statsByCampaign = CampaignEvent::userStats($timespan)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('F d, Y');
            });

        $stats = (new StatsTransformer)->transform($statsByCampaign, $timespan);

        return $stats;
    }
}
