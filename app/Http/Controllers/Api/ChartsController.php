<?php

namespace VideoAd\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Models\CampaignEvent;

/**
 * @author Coded Design
 * Class ChartsController
 * @package VideoAd\Http\Controllers\Api
 */
class ChartsController extends Controller
{
    public function test()
    {
        return collect([1, 2, 3, 4, 5, 5, 4, 1, 7, 6, 4, 9, 6, 4, 2, 1, 2, 3, 4, 5, 5, 4, 8, 9, 0, 0, 0, 0, 0, 0]);
    }

    /**
     * Return an array containing the daily requests per month.
     * @todo refactor this, use collections refactoring.
     * @return array
     */
    public function requests()
    {
        // fetch the count of 'requests' for every day.
        // this returns a collection of ex: [{'date': '2016-01-01', 'requests': 3}]
        $requestMonthlyCount = CampaignEvent::whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('created_at', '>=', Carbon::today()->startOfMonth())
            ->where('name', 'app')
            ->where('event', 'load')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([DB::raw('Date(created_at) as date'), DB::raw('count(*) as "requests"')]);

        return $this->transformToArrayOf('requests', $requestMonthlyCount);
    }

    /**
     * @return array
     */
    public function impressions()
    {
        // fetch the count of 'impressions' for every day.
        // this returns a collection of ex: [{'date': '2016-01-01', 'impressions': 3}]
        $impressionsMonthlyCount = CampaignEvent::whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('created_at', '>=', Carbon::today()->startOfMonth())
            ->where('name', 'ad')
            ->where('event', 'start')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([DB::raw('Date(created_at) as date'), DB::raw('count(*) as "impressions"')]);

        return $this->transformToArrayOf('impressions', $impressionsMonthlyCount);
    }

    /**
     * @param $statsType
     * @param $monthlyCount
     * @return array
     */
    protected function transformToArrayOf($statsType, $monthlyCount)
    {
        // create a collection containing the dates of the month.
        $daysOfTheMonth = collect(date_range(Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()));

        // transform the collection into the form of [{'date': '2016-11-11', 'statsType': 0}]
        $daysOfTheMonth->transform(function($date, $key) use ($statsType){
            return ['date' => $date->format("Y-m-d"), $statsType => 0];
        });

        // $monthlyCount can be: requests, impressions...
        foreach ($monthlyCount as $value) {
            // for each day of the month
            foreach ($daysOfTheMonth as $key => $day) {
                // check if we have a count in the db (campaign_events)
                if ($day['date'] == $value['date'] && $value[$statsType] > 0) {
                    // update the value of the 'statsType'.
                    $daysOfTheMonth[$key] = $value;
                }
            }
        }

        // return only the 'statsType' as array ordered by dates.
        return $daysOfTheMonth->map(function ($item) use ($statsType) {
            return $item[$statsType];
        });
    }
}
