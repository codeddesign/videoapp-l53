<?php

namespace App\Stats;

use Carbon\Carbon;

/**
 * @author Coded Design
 */
class StatsTransformer
{
    /**
     * Transform the data of the stats (requests, impressions...) to an array
     * that can be used by the charting library.
     *
     * @param $statsType
     * @param $monthlyCount
     * @return array
     */
    public function transformToArrayOf($statsType, $monthlyCount)
    {
        // create a collection containing the dates of the month.
        $daysOfTheMonth = collect(date_range(Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()));

        // transform the collection into the form of [{'date': '2016-11-11', 'statsType': 0}]
        $daysOfTheMonth->transform(function ($date, $key) use ($statsType) {
            return ['date' => $date->format('Y-m-d'), $statsType => 0];
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

    /**
     * This method allows us to use "transformToArrayOf{Impressions|Requests...}.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (preg_match('/^transformToArrayOf/', $method)) {
            $attribute = strtolower(substr($method, 18));
            array_unshift($arguments, $attribute);

            return call_user_func_array([$this, 'transformToArrayOf'], $arguments);
        }
    }
}
