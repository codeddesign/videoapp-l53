<?php

namespace VideoAd\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Models\CampaignEvent;

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

        // create a collection containing all the dates of the months
        $daysOfTheMonth = collect(date_range(Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()));

        // transform the collection to the form of: [{'date': '2016-01-01', 'requests': 0}}]
        $daysOfTheMonth = $daysOfTheMonth->map(function($day){
            return ['date' => $day->format("Y-m-d"), 'requests' => 0];
        });

        // compare the days of the month with the requests,
        // and update the dates that has requests
        foreach($requestMonthlyCount as $request) {
            foreach($daysOfTheMonth as $key => $day) {
                if($day['date'] == $request['date'] && $request['requests'] > 0) {
                    $daysOfTheMonth[$key] = $request;
                }
            }
        }

        // return only an array or requests sorted by the dates.
        // this is used for the chart, since we are not putting any labels for the dates
        // and just showing the requests.
        return $daysOfTheMonth->map(function($item) {
            return $item['requests'];
        });
    }
}
