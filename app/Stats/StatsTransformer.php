<?php

namespace App\Stats;

use App\Models\DateRange;
use App\Models\Tag;
use Illuminate\Support\Collection;

class StatsTransformer
{
    protected static $allStats = [
        'campaignRequests', 'tagRequests', 'impressions', 'fills', 'errors', 'revenue',
        'desktopPageviews', 'mobilePageviews',
    ];

    protected static $tagChartStats = [
        'desktopPrerollFill', 'mobilePrerollFill', 'desktopPrerollErrors', 'mobilePrerollErrors',
        'desktopOutstreamFill', 'mobileOutstreamFill', 'desktopOutstreamErrors', 'mobileOutstreamErrors',
        'desktopFill', 'mobileFill', 'desktopUserate', 'mobileUserate',
    ];

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
                    if (! isset($data[$key][$event->name])) {
                        continue;
                    }

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
            $data['tags'] = $this->tagStats();
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
                $this->parseTagStats($data['tags'], $stat);
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
                    $count         = $events->where('name', $stat)->sum('count');
                    $data[$stat][] = [$timestamp, $count];

                    if ($stat === 'impressions') {
                        $revenue           = $events->where('name', $stat)->sum(function ($impressions) {
                            return $this->calculateRevenue($impressions->count, $impressions->tag);
                        });
                        $data['revenue'][] = [$timestamp, $revenue];
                    }
                }

                if ($tagStats) {
                    $this->parseTagChart($data, $events, $timestamp);
                }
            } else {
                foreach (self::$allStats as $stat) {
                    $data[$stat][] = [$timestamp, 0];
                }

                if ($tagStats) {
                    foreach (self::$tagChartStats as $stat) {
                        $data[$stat][] = [$timestamp, 0];
                    }
                }
            }
        }

        return $data;
    }

    public function parseTagChart(&$data, $events, $timestamp)
    {
        $tagStats = $this->tagStats();

        foreach ($events as $event) {
            $this->parseTagStats($tagStats, $event);
        }

        $data['desktopPageviewsFill'][] = [
            $timestamp,
            Calculator::fillRate(
                $tagStats['desktop']['fills'],
                $events->where('name', 'desktopPageviews')->sum('count')
            ),
        ];

        $data['mobilePageviewsFill'][] = [
            $timestamp,
            Calculator::fillRate(
                $tagStats['mobile']['fills'],
                $events->where('name', 'mobilePageviews')->sum('count')
            ),
        ];

        $data['desktopPrerollFill'][]     = $this->calculateFillRate($tagStats['desktop']['preroll'], $timestamp);
        $data['mobilePrerollFill'][]      = $this->calculateFillRate($tagStats['mobile']['preroll'], $timestamp);
        $data['desktopPrerollErrors'][]   = $this->calculateErrorRate($tagStats['desktop']['preroll'], $timestamp);
        $data['mobilePrerollErrors'][]    = $this->calculateErrorRate($tagStats['mobile']['preroll'], $timestamp);
        $data['desktopOutstreamFill'][]   = $this->calculateFillRate($tagStats['desktop']['outstream'], $timestamp);
        $data['mobileOutstreamFill'][]    = $this->calculateFillRate($tagStats['mobile']['outstream'], $timestamp);
        $data['desktopOutstreamErrors'][] = $this->calculateErrorRate($tagStats['desktop']['outstream'], $timestamp);
        $data['mobileOutstreamErrors'][]  = $this->calculateErrorRate($tagStats['mobile']['outstream'], $timestamp);
        $data['desktopFill'][]            = [$timestamp, $tagStats['desktop']['fills']];
        $data['mobileFill'][]             = [$timestamp, $tagStats['mobile']['fills']];
        $data['desktopUserate'][]         = $this->calculateUseRate($tagStats['desktop']['outstream'], $timestamp);
        $data['mobileUserate'][]          = $this->calculateUseRate($tagStats['mobile']['outstream'], $timestamp);
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
            if (isset($data[$platform][$event->name])) {
                $data[$platform][$event->name] += $event->count;
            }

            foreach ($keys as $key) {
                if (isset($data[$platform][$key][$event->name])) {
                    $data[$platform][$key][$event->name] += $event->count;
                }
            }
        }
    }

    protected function tagStats()
    {
        return [
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

    protected function calculateFillRate($tagStats, $timestamp)
    {
        return [
            $timestamp,
            Calculator::fillRate(
                $tagStats['fills'],
                $tagStats['tagRequests']
            ),
        ];
    }

    protected function calculateErrorRate($tagStats, $timestamp)
    {
        return [
            $timestamp,
            Calculator::errorRate(
                $tagStats['errors'],
                $tagStats['tagRequests']
            ),
        ];
    }

    protected function calculateUseRate($tagStats, $timestamp)
    {
        return [
            $timestamp,
            Calculator::useRate(
                $tagStats['impressions'],
                $tagStats['fills']
            ),
        ];
    }

    protected function calculateRevenue($impressions, $tag)
    {
        return Calculator::revenue($impressions, $tag);
    }
}
