<?php

namespace App\Stats;

use App\Models\DateRange;
use Illuminate\Support\Collection;

class StatsTransformer
{
    protected static $allStats = ['tagRequests', 'impressions', 'fills', 'adErrors', 'revenue'];

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
                    'fills' => 0,

                    'preroll'   => [
                        'requests'    => 0,
                        'impressions' => 0,
                        'errors'      => 0,
                    ],
                    'outstream' => [
                        'requests'    => 0,
                        'impressions' => 0,
                        'errors'      => 0,
                    ],
                ],
                'desktop' => [
                    'fills' => 0,

                    'preroll'   => [
                        'requests'    => 0,
                        'impressions' => 0,
                        'errors'      => 0,
                    ],
                    'outstream' => [
                        'requests'    => 0,
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

    public function highcharts(Collection $stats, $format, $range, $step = null)
    {
        $dateRange = DateRange::byName($range);

        $data = [];

        foreach (self::$allStats as $stat) {
            $data[$stat] = [];
        }

        foreach ($dateRange->arrayByStep($step) as $period) {
            $key       = $period->format($format);
            $timestamp = $period->timestamp * 1000;

            if ($stats->has($key)) {
                $events = $stats->get($key);

                foreach (self::$allStats as $stat) {
                    if ($stat === 'revenue') {
                        continue;
                    }

                    if ($events->where('name', $stat)->isEmpty()) {
                        $data[$stat][] = [$timestamp, 0];
                    } else {
                        $count = $events->where('name', $stat)->sum('count');

                        if ($stat === 'impressions') {
                            $revenue = 0;
                            $events->where('name', $stat)->map(function ($impressions) use (&$revenue) {
                                if (isset($impressions->tag)) {
                                    $revenue += $this->calculateRevenue($impressions->count, $impressions->tag->ecpm);
                                }
                            });
                            $data['revenue'][] = [$timestamp, $revenue];
                        }

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

        // $stats contains the keys that will be increased
        $stats = [];
        switch ($event->name) {
            case 'impressions':
                $stats = ['impressions'];
                break;
            case 'fills':
                $stats = ['fills'];
                break;
            case 'tagRequests':
                $stats = ['requests'];
                break;
            case 'adErrors':
                $stats = ['errors'];
                break;
        }

        foreach ($platforms as $platform) {
            if($event->name === 'fills') {
                if (isset($data['tags'][$platform]['fills'])) {
                    $data['tags'][$platform]['fills'] += $event->count;
                }
            }

            foreach ($keys as $key) {
                foreach ($stats as $stat) {
                    if (isset($data['tags'][$platform][$key][$stat])) {
                        $data['tags'][$platform][$key][$stat] += $event->count;
                    }
                }
            }
        }
    }

    protected function calculateRevenue($impressions, $ecpm)
    {
        return ($impressions / 1000) * ($ecpm / 100);
    }
}
