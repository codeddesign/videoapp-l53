<?php

namespace App\Stats;

use App\Models\DateRange;
use App\Models\Tag;
use Illuminate\Support\Collection;

class StatsTransformer
{
    protected static $allStats = ['campaignRequests', 'tagRequests', 'impressions', 'fills', 'errors', 'revenue'];

    public function transformRealtime($stats)
    {
        $data = [];

        foreach (self::$allStats as $stat) {
            $data[$stat] = 0;
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
        $dateRange = DateRange::byName($range);

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
                'mobile'  => [
                    'fills'       => 0,
                    'impressions' => 0,

                    'preroll'   => [
                        'tagRequests' => 0,
                        'fills'       => 0,
                        'impressions' => 0,
                        'errors'      => 0,
                    ],
                    'outstream' => [
                        'tagRequests' => 0,
                        'fills'       => 0,
                        'impressions' => 0,
                        'errors'      => 0,
                    ],
                ],
                'desktop' => [
                    'fills'       => 0,
                    'impressions' => 0,

                    'preroll'   => [
                        'tagRequests' => 0,
                        'fills'       => 0,
                        'impressions' => 0,
                        'errors'      => 0,
                    ],
                    'outstream' => [
                        'tagRequests' => 0,
                        'fills'       => 0,
                        'impressions' => 0,
                        'errors'      => 0,
                    ],
                ],
            ];
        }

        foreach ($stats as $stat) {
            if (! isset($data[$stat->name])) {
                continue;
            }

            $data[$stat->name] += $stat->count;

            if ($stat->name === 'impressions' && isset($stat->tag)) {
                $data['revenue'] += $this->calculateRevenue($stat->count, $stat->tag);
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

    public function highcharts(Collection $stats, $format, $range, $step = null, $tagStats = false)
    {
        $dateRange = DateRange::byName($range);

        $data = [];

        //Pre-fill the $data array
        foreach (self::$allStats as $stat) {
            $data[$stat] = [];
        }

        //Loop through all date periods
        foreach ($dateRange->arrayByStep($step) as $period) {
            $key       = $period->format($format);
            $timestamp = $period->timestamp * 1000;

            if ($stats->has($key)) {
                $events = $stats->get($key);

                //Sum all the desired stats. ('revenue' is inferred through impressions)
                foreach (array_diff(self::$allStats, ['revenue']) as $stat) {
                    if ($events->where('name', $stat)->isEmpty()) {
                        $data[$stat][] = [$timestamp, 0];
                    } else {
                        $count = $events->where('name', $stat)->sum('count');

                        $data[$stat][] = [$timestamp, $count];
                    }

                    if ($stat === 'impressions') {
                        $revenue           = $events->where('name', $stat)->sum(function ($impressions) {
                            return $this->calculateRevenue($impressions->count, $impressions->tag);
                        });
                        $data['revenue'][] = [$timestamp, $revenue];
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

    public function highchartsTagStats()
    {
    }

    protected function parseTagStats(&$data, $event)
    {
        if (! isset($event->tag)) {
            return;
        }

        $tag = $event->tag;

        if ($tag->platform_type === 'all') {
            $platforms = ['desktop', 'mobile'];
        } else {
            $platforms = [$tag->platform_type];
        }

        $keys = $tag->campaign_types;

        if ($tag->ad_type === 'all') {
            $keys[] = 'instream';
            $keys[] = 'outstream';
        } else {
            $keys[] = $tag->ad_type;
        }

        foreach ($platforms as $platform) {
            if (isset($data['tags'][$platform][$event->name])) {
                $data['tags'][$platform][$event->name] += $event->count;
            }

            foreach ($keys as $key) {
                if (isset($data['tags'][$platform][$key][$event->name])) {
                    $data['tags'][$platform][$key][$event->name] += $event->count;
                }
            }
        }
    }

    protected function calculateRevenue($impressions, $tag)
    {
        if (! $tag) {
            return 0;
        }

        return floatval(number_format(($impressions / 1000) * ($tag->ecpm / 100), 2, '.', ''));
    }
}
