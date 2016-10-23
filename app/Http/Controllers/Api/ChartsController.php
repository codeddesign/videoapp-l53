<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampaignEvent;
use App\Stats\StatsTransformer;
use Carbon\Carbon;

class ChartsController extends Controller
{
    /**
     * @param StatsTransformer $statsTransformer
     *
     * @return array
     */
    public function stats(StatsTransformer $statsTransformer)
    {
        $requests    = $this->requests($statsTransformer);
        $impressions = $this->impressions($statsTransformer);
        $revenue     = collect($impressions)->map(function ($value) {
            return (4 * $value) / 1000;
        });

        $now = Carbon::now();

        $requests= [];
        $impressions=[];
        $revenue=[];
        
        for($i=0; $i<=30; $i++) {
            $requests[] = [
                $now->copy()->subMinutes(30-$i)->timestamp*1000,
                mt_rand(20000,100000)
            ];
            $impressions[] = [
                $now->copy()->subMinutes(30-$i)->timestamp*1000,
                mt_rand(200000,1000000)
            ];
            $revenue[] = [
                $now->copy()->subMinutes(30-$i)->timestamp*1000,
                mt_rand(300,1500)
            ];
        }

        return [
            'requests'    => $requests,
            'impressions' => $impressions,
            'revenue'     => $revenue,
        ];
    }

    /**
     * Return an array of the requests count, ordered by the days of the month.
     *
     * @return array
     */
    public function requests(StatsTransformer $statsTransformer)
    {
        return $statsTransformer->transformToArrayOfRequests(CampaignEvent::requestsStats());
    }

    /**
     * Return an array of the impressions count, ordered by the days of the month.
     *
     * @return array
     */
    public function impressions(StatsTransformer $statsTransformer)
    {
        return $statsTransformer->transformToArrayOfImpressions(CampaignEvent::impressionsStats());
    }
}
