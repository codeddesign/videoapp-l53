<?php

namespace App\Stats;

use App\Models\DateRange;
use App\Models\Tag;
use Illuminate\Support\Collection;

class StatsTransformer
{
    protected static $allStats = [
        'campaignRequests', 'tagRequests', 'impressions', 'fills', 'errors', 'revenue',
        'desktopPageviews', 'mobilePageviews', 'backfill', 'desktopBackfillRevenue', 'mobileBackfillRevenue',
    ];

    protected static $tagChartStats = [
        'desktopPrerollFill', 'mobilePrerollFill', 'desktopPrerollErrors', 'mobilePrerollErrors',
        'desktopOutstreamFill', 'mobileOutstreamFill', 'desktopOutstreamErrors', 'mobileOutstreamErrors',
        'desktopFill', 'mobileFill', 'desktopUserate', 'mobileUserate', 'desktopPageviewsFill', 'mobilePageviewsFill',
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

            if ($stat->name === 'backfill' && isset($stat->backfill)) {
                switch ($stat->backfill->platform_type) {
                    case 'mobile':
                        $data['mobileBackfillRevenue'] += $this->calculateRevenue($stat->count, $stat->backfill);
                        break;
                    case 'desktop':
                        $data['desktopBackfillRevenue'] += $this->calculateRevenue($stat->count, $stat->backfill);
                        break;
                }
            }

            if ($tagStats) {
                $this->parseTagStats($data['tags'], $stat);
            }
        }

        return $data;
    }

    /**
     * @param \Illuminate\Support\Collection $stats
     * @param                                $count
     *
     * @return \Illuminate\Support\Collection
     */
    public function sumAllAndAverage(Collection $stats, $count)
    {
        $data = collect($this->transformSumAll($stats));

        if ($count === 1) {
            return $data;
        }

        $data = $data->map(function ($item, $key) use ($count) {
            if ($key === 'revenue') {
                return Calculator::decimals($item / $count);
            }

            return round($item / $count);
        });

        return $data;
    }

    public function highcharts(Collection $stats, $format, $dateRange, $tagStats = false)
    {
        $data = [];

        //Pre-fill the $data array
        foreach (self::$allStats as $stat) {
            $data[$stat] = [];
        }

        //Loop through all date periods
        foreach ($dateRange->arrayByStep() as $period) {
            $key       = $period->format($format);
            $timestamp = $period->timestamp * 1000;

            if ($stats->has($key)) {
                $events = $stats->get($key);

                //Sum all the desired stats. ('revenue' is inferred through impressions)
                foreach (array_diff(self::$allStats, ['revenue', 'mobileBackfillRevenue', 'desktopBackfillRevenue']) as $stat) {
                    $count         = $events->where('name', $stat)->sum('count');
                    $data[$stat][] = [$timestamp, $count];

                    if ($stat === 'impressions') {
                        $revenue           = $events->where('name', $stat)->sum(function ($impressions) {
                            return $this->calculateRevenue($impressions->count, $impressions->tag);
                        });
                        $data['revenue'][] = [$timestamp, $revenue];
                    }

                    if ($stat === 'backfill') {
                        $desktopRevenue = $events->where('name', $stat)
                            ->sum(function ($backfill) {
                                if ($backfill->backfill->platform_type !== 'desktop') {
                                    return 0;
                                }

                                return $this->calculateRevenue($backfill->count, $backfill->backfill);
                            });

                        $mobileRevenue = $events->where('name', $stat)
                            ->sum(function ($backfill) {
                                if ($backfill->backfill->platform_type !== 'mobile') {
                                    return 0;
                                }

                                return $this->calculateRevenue($backfill->count, $backfill->backfill);
                            });

                        $data['desktopBackfillRevenue'][] = [$timestamp, $desktopRevenue];
                        $data['mobileBackfillRevenue'][]  = [$timestamp, $mobileRevenue];
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

public
function parseTagChart(&$data, $events, $timestamp)
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

protected
function parseTagStats(&$data, $event)
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

    $keys = $tag->ad_types;

    if ($tag->ad_type === 'all') {
        $keys[] = 'instream';
        $keys[] = 'outstream';
    } else {
        $keys[] = $tag->type;
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

protected
function tagStats()
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

public
function combineWebsites(Collection $stats)
{
    $filtered = $stats->filter(function ($stat) {
        return array_key_exists('website', $stat) && (
                $stat['desktopPageviews'] + $stat['mobilePageviews'] === 0
            );
    });

    $pageviews = $stats->filter(function ($stat) {
        return array_key_exists('website', $stat) && ($stat['desktopPageviews'] + $stat['mobilePageviews'] > 0);
    });

    $filtered = $filtered->map(function ($stat) use ($pageviews) {
        $websitePageviews = $pageviews->where('website', $stat['website']);

        if (! isset($stat['platform_type'])) {
            return $stat;
        }

        switch ($stat['platform_type']) {
            case 'desktop':
                $stat['desktopPageviews'] += $websitePageviews->sum('desktopPageviews');
                break;
            case 'mobile':
                $stat['mobilePageviews'] += $websitePageviews->sum('mobilePageviews');
                break;
        }

        unset($stat['advertiser'], $stat['tag_type'], $stat['description'], $stat['ad_type']);

        return $stat;
    });

    return $filtered;
}

protected
function calculateFillRate($tagStats, $timestamp)
{
    return [
        $timestamp,
        Calculator::fillRate(
            $tagStats['fills'],
            $tagStats['tagRequests']
        ),
    ];
}

protected
function calculateErrorRate($tagStats, $timestamp)
{
    return [
        $timestamp,
        Calculator::errorRate(
            $tagStats['errors'],
            $tagStats['tagRequests']
        ),
    ];
}

protected
function calculateUseRate($tagStats, $timestamp)
{
    return [
        $timestamp,
        Calculator::useRate(
            $tagStats['impressions'],
            $tagStats['fills']
        ),
    ];
}

protected
function calculateRevenue($impressions, $tag)
{
    return Calculator::revenue($impressions, $tag);
}
}
