<?php

namespace App\Services\Reports;

use App\Models\CampaignEvent;
use App\Models\Report;
use App\Models\Tag;
use App\Sessions\SessionsCollection;
use App\Stats\Calculator;
use App\Stats\StatsTransformer;
use Illuminate\Support\Collection;

class Reports
{
    public function stats(Report $report, $filterMetrics = true)
    {
        $reportQuery = new Query($report);

        $combineBy = $reportQuery->mapTypeToModel($report->combine_by);
        $sortBy    = $reportQuery->mapTypeToModel($report->sort_by);

        $relations = [$combineBy['relation'], $sortBy['relation']];

        $pageviews = ($combineBy['relation'] === 'website' && $sortBy['property'] === 'platform_type' ||
            $combineBy['property'] === 'platform_type' && $sortBy['relation'] === 'website');

        $reportEvents = $reportQuery->campaignEvents($report)
            ->filter(function ($event) use ($report, $combineBy, $sortBy, $pageviews, $relations) {
                if ($pageviews) {
                    return true;
                }

                if (in_array($event->name, ['mobilePageviews', 'desktopPageviews']) &&
                    ! in_array('website', $relations)) {
                    return false;
                }

                return true;
            })
            ->groupBy(function ($item) use ($combineBy, $sortBy) {
                $groupBy = callModule($item, $combineBy['relation'].'.'.$combineBy['property'], '.');

                $groupBy .= callModule($item, $sortBy['relation'].'.'.$sortBy['property'], '.');

                return $groupBy;
            });

        $reportStats      = new Collection;
        $statsTransformer = new StatsTransformer;

        foreach ($reportEvents as $events) {
            $parsedStats = $statsTransformer->transformSumAll($events);

            $tag = $events->first()->tag;
            if (!($tag instanceof Tag)) {
                continue;
            }

            $campaign = $events->first()->campaign;
            $website  = $events->first()->website;

            $stats = new Collection;

            // Ensure the combine_by field is always
            // the first item in the stats array
            $stats = $stats->merge([
                $report->combine_by => '',
            ]);

            if ($campaign) {
                $stats = $stats->merge([
                    'ad_type' => $campaign->type->adType->name,
                ]);
            }

            if ($tag && in_array('tag', $relations)) {
                foreach ([$combineBy, $sortBy] as $item) {
                    if ($item['relation'] === 'tag') {
                        $stats = $stats->merge([
                            $item['property'] => $tag->{$item['property']},
                        ]);
                    }
                }
            }

            if (in_array('website', $relations)) {
                $stats = $stats->merge([
                    'website'          => $website->domain ?? 'N/A',
                    'desktopPageviews' => $parsedStats['desktopPageviews'],
                    'mobilePageviews'  => $parsedStats['mobilePageviews'],
                ]);
            }

            $sessions = new SessionsCollection($events->where('name', 'sessions'));

            $stats = $stats->merge([
                'requests'     => $parsedStats['tagRequests'],
                'impressions'  => $parsedStats['impressions'],
                'fills'        => $parsedStats['fills'],
                'rpm'          => $sessions->rpm(),
                'fill_rate'    => $this->calculatePercentage($parsedStats['fills'], $parsedStats['tagRequests']),
                'pv_fill_rate' => $this->calculatePercentage(
                    $parsedStats['impressions'],
                    $parsedStats['desktopPageviews'] + $parsedStats['mobilePageviews']
                ),
                'revenue'      => Calculator::decimals($parsedStats['revenue']),
                'cpm'          => Calculator::ecpm($parsedStats['revenue'], $parsedStats['impressions']),
                'errors'       => $parsedStats['errors'],
                'error_rate'   => $this->calculatePercentage($parsedStats['errors'], $parsedStats['tagRequests']),
            ]);

            $stats = $stats->merge($this->parseViewership($events, $stats));
            $stats = $stats->merge($this->parseErrors($events));

            if ($filterMetrics && $report->included_metrics) {
                $pageviewsFilter = $pageviews ? ['desktopPageviews', 'mobilePageviews'] : [];
                $stats           = $stats->filter(function ($value, $key) use ($report, $pageviewsFilter) {
                    return in_array($key, array_merge(
                        $report->included_metrics,
                        [$report->sort_by, $report->combine_by],
                        $pageviewsFilter
                    ));
                });
            }

            $reportStats->push($stats->toArray());
        }

        if ($combineBy['relation'] === 'website' && $sortBy['property'] === 'platform_type' ||
            $combineBy['property'] === 'platform_type' && $sortBy['relation'] === 'website'
        ) {
            $reportStats = $statsTransformer->combineWebsites(
                $reportStats
            );
        }

        $reportStats = $reportStats->sortByDesc($report->sort_by);

        $reportStats = $this->calculateTotals($reportStats);

        return $reportStats->values();
    }

    protected function calculateTotals(Collection $events)
    {
        if ($events->count() === 0) {
            return $events;
        }

        $totals = [
            'desktopPageviews', 'mobilePageviews', 'requests', 'impressions', 'fills', 'revenue', 'errors',
            'loaded', 'start', 'firstquartile', 'midpoint', 'thirdquartile', 'complete', 'click', 'pause',
            'click',
        ];

        foreach (CampaignEvent::$errors as $error) {
            $totals[] = 'error'.$error;
        }

        $totals = array_fill_keys($totals, 0);

        foreach ($totals as $key => $value) {
            $totals[$key] = $events->sum($key);
        }

        $newLine = [];

        foreach (array_keys($events->first()) as $key) {
            $newLine[$key] = $totals[$key] ?? '—';
        }

        $events->push($newLine);

        return $events;
    }

    /**
     * @param $events
     *
     * @return \Illuminate\Support\Collection
     */
    protected function parseErrors($events)
    {
        $errorCodes = collect(CampaignEvent::$errors);
        $errors     = new Collection;

        foreach ($errorCodes as $code) {
            $errors->put("error{$code}", 0);
        }

        foreach ($events as $event) {
            if ($event->name === 'errors') {
                if (isset($errors["error{$event->status}"])) {
                    $errors["error{$event->status}"] += $event->count;
                }
            }
        }

        return $errors;
    }

    protected function parseViewership($events, $tagStats)
    {
        $viewershipCodes = collect(CampaignEvent::$viewership);
        $viewership      = new Collection;

        foreach ($viewershipCodes as $code => $name) {
            $viewership->put($name, 0);
        }

        foreach ($events as $event) {
            if ($event->name === 'viewership') {
                if ($viewershipCodes->has($event->status)) {
                    $viewership[$viewershipCodes[$event->status]] += $event->count;
                }
            }
        }
        if ($tagStats['impressions'] !== 0) {
            $viewership['ctr']             = number_format(($viewership['click'] / $tagStats['impressions']) * 100, 2);
            $viewership['completion_rate'] = number_format(($viewership['complete'] / $tagStats['impressions']) * 100, 2);
        } else {
            $viewership['ctr']             = 0.0;
            $viewership['completion_rate'] = 0.0;
        }

        return $viewership;
    }

    protected function calculatePercentage($dividend, $divisor)
    {
        return Calculator::percentage($dividend, $divisor);
    }
}
