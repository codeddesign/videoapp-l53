<?php

namespace App\Stats;

use App\Models\DateRange;
use Illuminate\Support\Collection;

class StatsTransformer
{
    protected static $allStats = ['requests', 'impressions', 'fills', 'fillErrors', 'adErrors', 'revenue'];

    public function transformRealtime($stats)
    {
        $data = [];

        foreach (self::$allStats as $stat) {
            $data[$stat] = 0;
        }

        dd($stats);

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
     * @param bool                           $tagStats
     *
     * @return array
     */
    public function transformSumAll(Collection $stats, $tagStats = false)
    {
        $data = [];

        // Initialize all stats with 0
        foreach (self::$allStats as $stat) {
            $data[$stat] = 0;
        }

        if ($tagStats) {
            $data['tags'] = [
                'mobile' => [
                    'preroll' => [
                        'requests' => 0,
                        'impressions' => 0,
                        'errors' => 0,
                    ],
                    'outstream' => [
                        'requests' => 0,
                        'impressions' => 0,
                        'errors' => 0,
                    ],
                ],
                'desktop' => [
                    'preroll' => [
                        'requests' => 0,
                        'impressions' => 0,
                        'errors' => 0,
                    ],
                    'outstream' => [
                        'requests' => 0,
                        'impressions' => 0,
                        'errors' => 0,
                    ],
                ],
            ];
        }

        foreach ($stats as $stat) {
            $data[$stat->name] += $stat->count;

            if ($stat->name === 'impressions') {
                $data['revenue'] += $this->calculateRevenue($stat->count, $stat->tag->ecpm);
            }

            if ($tagStats) {
                $this->parseTagStats($data, $stat);
            }
        }

        return $data;
    }

    public function sumAllAndAverage(Collection $stats, $count)
    {
        $data = collect($this->transformSumAll($stats));

        $data = $data->map(function ($item) use ($count) {
            return round($item / $count);
        });

        return $data;
    }

    //TODO: Refactor so we just use the highcharts() function
    public function transformHighcharts($type, Collection $stats, $format, $range, $step = null)
    {
        $dateRange = call_user_func(DateRange::class.'::'.$range);

        $data = [];

        foreach ($dateRange->arrayByStep($step) as $period) {
            // The array key format is type-mm/dd/YYYY
            // Example: requests-10/31/2016
            $key = $period->format($format);
            $timestamp = $period->timestamp * 1000;

            if ($stats->has($key)) {
                $count = $stats->get($key)->sum('count');
                $data[] = [$timestamp, $count];
            } else {
                $data[] = [$timestamp, 0];
            }
        }

        return $data;
    }

    public function highcharts(Collection $stats, $format, $range, $step = null)
    {
        $dateRange = call_user_func(DateRange::class.'::'.$range);

        $data = [];

        foreach (self::$allStats as $stat) {
            $data[$stat] = [];
        }

        foreach ($dateRange->arrayByStep($step) as $period) {
            $key = $period->format($format);
            $timestamp = $period->timestamp * 1000;

            if ($stats->has($key)) {
                $events = $stats->get($key);

                foreach (self::$allStats as $stat) {
                    if ($events->where('name', $stat)->isEmpty()) {
                        $data[$stat][] = [$timestamp, 0];
                    } else {
                        $count = $events->where('name', $stat)->sum('count');
                        $data[$stat][] = [$timestamp, $count];
                    }
                }
            } else {
                foreach (self::$allStats as $stat) {
                    $data[$stat][] = [$timestamp, 0];
                }
            }
        }

        return $data;
    }

    protected function parseTagStats(&$data, $stat)
    {
        $statName = $stat->name;

        if ($statName === 'adErrors' || $statName === 'fillErrors') {
            $statName = 'errors';
        }

        $tag = $stat->tag;

        if (isset($data['tags'][$tag->platform_type][$tag->ad_type][$statName])) {
            $data['tags'][$tag->platform_type][$tag->ad_type][$statName] += $stat->count;
        }

        foreach ($tag->campaign_types as $type) {
            if (isset($data['tags'][$tag->platform_type][$type][$statName])) {
                $data['tags'][$tag->platform_type][$type][$statName] += $stat->count;
            }
        }
    }

    protected function calculateRevenue($impressions, $ecpm)
    {
        return ($impressions / 1000) * ($ecpm / 100);
    }
}
