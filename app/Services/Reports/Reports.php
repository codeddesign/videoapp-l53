<?php

namespace App\Services\Reports;

use App\Models\CampaignEvent;
use App\Models\Report;
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

        $reportEvents = $reportQuery->campaignEvents($report)
            ->filter(function ($event) use ($report, $combineBy, $sortBy) {
                if (in_array('website', [$combineBy['relation'], $sortBy['property']]) ||
                    ($combineBy['relation'] === 'website' && $sortBy['property'] === 'platform_type' ||
                        $combineBy['property'] === 'platform_type' && $sortBy['relation'] === 'website')
                ) {
                    return true;
                }

                if (in_array($event->name, ['mobilePageviews', 'desktopPageviews'])) {
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

            $campaign = $events->first()->campaign;
            $tag      = $events->first()->tag;
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

            $relations = [$combineBy['relation'], $sortBy['relation']];

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

            $stats = $stats->merge([
                'requests'    => $parsedStats['tagRequests'],
                'impressions' => $parsedStats['impressions'],
                'fills'       => $parsedStats['fills'],
                'fill_rate'   => $this->calculatePercentage($parsedStats['fills'], $parsedStats['tagRequests']),
                'revenue'     => Calculator::decimals($parsedStats['revenue']),
                'cpm'        => Calculator::ecpm($parsedStats['revenue'], $parsedStats['impressions']),
                'errors'      => $parsedStats['errors'],
                'error_rate'  => $this->calculatePercentage($parsedStats['errors'], $parsedStats['tagRequests']),
            ]);

            $stats = $stats->merge($this->parseViewership($events, $stats));
            $stats = $stats->merge($this->parseErrors($events));

            if ($filterMetrics && $report->included_metrics) {
                $stats = $stats->filter(function ($value, $key) use ($report) {
                    return in_array($key, array_merge(
                        $report->included_metrics,
                        [$report->sort_by],
                        ['desktopPageviews', 'mobilePageviews']
                    ));
                });
            }

            $reportStats->push($stats->toArray());
        }

        if ($combineBy['relation'] === 'website' && $sortBy['property'] === 'platform_type' ||
            $combineBy['property'] === 'platform_type' && $sortBy['relation'] === 'website'
        ) {
            $reportStats = $statsTransformer->combineWebsites($reportStats);
        }

        $reportStats = $reportStats->sortByDesc($report->sort_by);

        return $reportStats->values();
    }

    protected function calculateTotals($events)
    {

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
        if ($divisor === 0) {
            return '0.00';
        }

        return number_format(($dividend / $divisor * 100), 2);
    }
}
