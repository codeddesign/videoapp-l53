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
        } else {
            unset($stats['tags']);
        }

        return $stats;
    }

    /**
     * Transforms an array of Campaign Events into
     * daily stats given by the $range variable
     *
     * @param \Illuminate\Support\Collection $stats
     * @param                                $range
     *
     * @return array
     */
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

    /**
     * Transforms an array of Campaign Events into
     * a single array with all the summed values
     *
     * @param \Illuminate\Support\Collection $stats
     *
     * @return array
     */
    public function transformSumAll(Collection $stats) {
        $data = [];

        // Initialize all stats with 0
        foreach (self::$allStats as $stat) {
            $data[$stat] = 0;
        }

        foreach($stats as $stat) {
            $data[$stat->name] += $stat->count;
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
                $count = $stats->get($key)->sum('count');
                $data[] = [$timestamp, $count];
            } else {
                $data[] = [$timestamp, 0];
            }
        }

        return $data;
    }
}
