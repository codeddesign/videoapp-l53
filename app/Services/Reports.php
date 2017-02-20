<?php

namespace App\Services;

use App\Mail\ScheduledReport;
use App\Models\CampaignEvent;
use App\Models\Report;
use App\Models\User;
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
            return "{$item->tag_id}-{$item->website_id}";
        });

        $stats            = new Collection;
        $statsTransformer = new StatsTransformer;

        foreach ($events as $tagEvents) {
            $parsedStats = $statsTransformer->transformSumAll($tagEvents);

            $tag = $tagEvents->first()->tag;

            //The tag may have been deleted
            if (! $tag) {
                continue;
            }

            $tagStats = collect([
                'advertiser'    => $tag->advertiser,
                'description'   => $tag->description,
                'ad_type'       => $tag->ad_type,
                'platform_type' => $tag->platform_type,
                'website'       => $tagEvents->first()->website->domain ?? 'N/A',
                'requests'      => $parsedStats['tagRequests'],
                'impressions'   => $parsedStats['impressions'],
                'fills'         => $parsedStats['fills'],
                'fill_rate'     => $this->calculatePercentage($parsedStats['fills'], $parsedStats['tagRequests']),
                'revenue'       => number_format($parsedStats['revenue'], 2),
                'ecpm'          => $tag->ecpm / 100.0,
                'errors'        => $parsedStats['errors'],
                'error_rate'    => $this->calculatePercentage($parsedStats['errors'], $parsedStats['tagRequests']),
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

        return $stats->values()->toArray();
    }

    /**
     * @param \App\Models\Report $report
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function generateXls(Report $report)
    {
        $stats = collect($this->stats($report));

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

    public function createDefaultReports(User $user)
    {
        $reports = new Collection([
            [
                'title'      => 'Today',
                'date_range' => 'today',
            ],
            [
                'title'      => 'Yesterday',
                'date_range' => 'yesterday',
            ],
            [
                'title'      => 'Last 3 Days',
                'date_range' => 'lastThreeDays',
            ],
            [
                'title'      => 'Last 7 Days',
                'date_range' => 'lastSevenDays',
            ],
            [
                'title'      => 'Last 30 Days',
                'date_range' => 'lastThirtyDays',
            ],
            [
                'title'      => 'This Month',
                'date_range' => 'thisMonth',
            ],
            [
                'title'      => 'Last Month',
                'date_range' => 'lastMonth',
            ],
        ]);

        $reports = $reports->map(function ($report) use ($user) {
            return array_merge($report, [
                'user_id'          => $user->id,
                'recipient'        => $user->email,
                'sort_by'          => 'advertiser',
                'schedule'         => 'once',
                'deletable'        => false,
                'included_metrics' => json_encode(Report::allMetrics()),
                'filter'           => json_encode([
                    'type'   => '',
                    'value'  => '',
                    'filter' => '',
                ]),
            ]);
        });

        Report::saveMany($reports);
    }

    /**
     * @param \App\Models\Report $report
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function campaignEvents(Report $report)
    {
        $stats = CampaignEvent::query()->with('tag', 'website');

        $stats = $report->filterQuery($stats);

        $dateRange = $report->dateRange();
        $stats     = $stats->timeRange($dateRange, auth()->user()->timezone)
            ->where('tag_id', '!=', null);

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
