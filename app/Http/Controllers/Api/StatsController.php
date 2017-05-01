<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEvent;
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
        $ids = $this->user->campaigns->pluck('id')->toArray();
        $stats = (new CampaignEvents)->fetchMultipleCampaigns($ids, true);

        $cache = app(Repository::class);

        /*$databaseEvents = $cache->tags(['events'])->remember("user.{$this->user->id}.events.today}", 30, function () {
            return $this->fetchHistoricalData('today');
        });*/

        $databaseEvents = $this->campaignEvents('today');

        $stats = $stats->merge($databaseEvents);

        return (new StatsTransformer)->transformSumAll($stats, false);
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
        $statsByCampaign = CampaignEvent::userStats($timespan)
            ->with('tag', 'backfill')
            ->select('name', 'tag_id', 'backfill_id', DB::raw('created_at::date'), DB::raw('SUM(count) as count'))
            ->where('name', '!=', 'viewership')//viewership data isn't charted
            ->groupBy('name', 'tag_id', 'backfill_id', DB::raw('created_at::date'))
            ->get();

        return $statsByCampaign;
    }
}
