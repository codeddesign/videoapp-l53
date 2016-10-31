<?php

namespace App\Stats;

use App\Models\DateRange;
use Illuminate\Support\Collection;

class StatsTransformer
{
    protected static $allStats = ['requests', 'impressions', 'fills', 'fillErrors', 'adErrors'];

    public function transform(Collection $stats, $range)
    {
        $dateRange = call_user_func(DateRange::class.'::'.$range);

        $data = [];

        // Loop through all days in the given range
        foreach ($dateRange->arrayByStep() as $day) {
            $key = $day->format('F d, Y');

            // If this day has any data
            if ($stats->has($key)) {
                $events = $stats->get($key);

                foreach ($events as $event) {
                    $data[$key][$event->name] = $event->count;
                }

                // If some stat was not available in the stats, it should be 0
                if ($missingKeys = array_diff(self::$allStats, array_keys($data[$key]))) {
                    foreach ($missingKeys as $missingKey) {
                        $data[$key][$missingKey] = 0;
                    }
                }
            } else {
                // If this day has no data, all keys should be 0
                foreach (self::$allStats as $stat) {
                    $data[$key][$stat] = 0;
                }
            }
        }

        return $data;
    }

    public function transformHighcharts($type, Collection $stats, $range)
    {
        $dateRange = call_user_func(DateRange::class.'::'.$range);

        $data = [];

        foreach ($dateRange->arrayByStep() as $day) {
            // The array key format is type-mm/dd/YYYY
            // Example: requests-10/31/2016
            $key = $type.'-'.$day->format('m/d/Y');
            $timestamp = $day->timestamp * 1000;

            if ($stats->has($key)) {
                $item = $stats->get($key)[0];
                $data[] = [$timestamp, $item->count];
            } else {
                $data[] = [$timestamp, 0];
            }
        }

        return $data;
    }
}
