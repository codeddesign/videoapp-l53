<?php

namespace App\Services;

use App\Mail\ScheduledReport;
use App\Models\CampaignEvent;
use App\Models\Report;
use App\Stats\StatsTransformer;
use App\Transformers\Spreadsheet\ReportTransformer;
use Carbon\Carbon;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Collection;

class Reports
{
    public function process(Report $report)
    {
        $file = $this->generateXls($report);
        $this->sendReport($report, $file);
    }

    public function stats(Report $report, $filterMetrics = true)
    {
        $events = $this->campaignEvents($report)->groupBy(function ($item) {
            return $item->tag_id;
        });

        $stats            = new Collection;
        $statsTransformer = new StatsTransformer;

        foreach ($events as $tagEvents) {
            $parsedStats = $statsTransformer->transformSumAll($tagEvents);

            if (! isset($tagEvents->first()->tag) || $parsedStats['impressions'] === 0) {
                continue;
            }

            $tag = $tagEvents->first()->tag;

            $tagStats = collect([
                'advertiser'    => $tag->advertiser,
                'description'   => $tag->description,
                'ad_type'       => $tag->ad_type,
                'platform_type' => $tag->platform_type,
                'requests'      => $parsedStats['impressions'] + $parsedStats['fillErrors'],
                'impressions'   => $parsedStats['impressions'],
                'fills'         => $parsedStats['fills'],
                'fill_rate'     => number_format(($parsedStats['impressions'] / ($parsedStats['impressions'] + $parsedStats['fillErrors']) * 100), 2),
                'revenue'       => number_format($parsedStats['revenue'], 2),
                'ecpm'          => $tag->ecpm / 100.0,
                'errors'        => $parsedStats['adErrors'],
                'error_rate'    => number_format(($parsedStats['adErrors'] / $parsedStats['impressions'] * 100), 2),
            ]);

            $tagStats = $tagStats->merge($this->parseViewership($tagEvents, $tagStats));
            $tagStats = $tagStats->merge($this->parseErrors($tagEvents));

            if ($filterMetrics && $report->included_metrics) {
                $tagStats = $tagStats->filter(function ($value, $key) use ($report) {
                    return in_array($key, array_merge(
                        $report->included_metrics,
                        [$report->sort_by],
                        Report::$fixedSpreadsheetHeader
                    ));
                });
            }

            $stats->push($tagStats->toArray());
        }

        $stats = $stats->sortBy($report->sort_by);

        return $stats;
    }

    /**
     * @param \App\Models\Report $report
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function generateXls(Report $report)
    {
        $stats = $this->stats($report);

        $headerRows = [
            ['Report Name', $report->title],
            ['Generated At', Carbon::now()->format('F j, Y g:i A e')],
            ['Date Range', "{$report->dateRange()->from->toFormattedDateString()} - {$report->dateRange()->to->toFormattedDateString()}"],
            $report->spreadsheetHeader($stats),
        ];

        $spreadsheet = new Spreadsheet;

        $file = $spreadsheet->xlsxFile($stats, new ReportTransformer, $headerRows);

        $report->last_generated_at = Carbon::now();
        $report->save();

        return $file;
    }

    public function sendReport(Report $report, $file)
    {
        /** @var Mailer $mailer */
        $mailer = app(Mailer::class);

        $mailer->to($report->recipient)->send(new ScheduledReport($report, $file));
    }

    /**
     * @param \App\Models\Report $report
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function campaignEvents(Report $report)
    {
        $stats = CampaignEvent::query()->with('tag');

        $stats = $report->filterQuery($stats);

        $dateRange = $report->dateRange();
        $stats     = $stats->where('created_at', '>=', $dateRange->from)
            ->where('created_at', '<=', $dateRange->to);

        return $stats->get();
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
            if ($event->name === 'adErrors') {
                $errors["error{$event->status}"] += $event->count;
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
                if($viewershipCodes->has($event->status)) {
                    $viewership[$viewershipCodes[$event->status]] += $event->count;
                }
            }
        }

        $viewership['ctr'] = number_format(($viewership['click'] / $tagStats['impressions']) * 100, 2);
        $viewership['completion_rate'] = number_format(($viewership['complete'] / $tagStats['impressions']) * 100, 2);

        return $viewership;
    }
}
