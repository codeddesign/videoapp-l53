<?php

namespace App\Stats;

use App\Models\DateRange;
use Illuminate\Support\Collection;

class StatsTransformer
{
    protected static $allStats = ['requests', 'impressions', 'fills', 'fillErrors', 'adErrors'];

    public function transformRealtime($stats)
    {
        if ($stats === null) {
            $stats = [];

            foreach (self::$allStats as $stat) {
                $stats[$stat] = 0;
            }
        }

        return $stats;
    }

    public function transform(Collection $stats, $range)
    {
        $dateRange = call_user_func(DateRange::class.'::'.$range);

        $data = [];

        // Loop through all days in the given range
        foreach ($dateRange->arrayByStep() as $day) {
            $key = $day->format('F d, Y');

            // Initialize all stats with 0
            foreach (self::$allStats as $stat) {
                $data[$key][$stat] = 0;
            }

            // Sum the data
            if ($stats->has($key)) {
                $events = $stats->get($key);

                foreach ($events as $event) {
                    $data[$key][$event->name] += $event->count;
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
