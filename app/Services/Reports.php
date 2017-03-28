<?php

namespace App\Services;

use App\Mail\ScheduledReport;
use App\Models\CampaignEvent;
use App\Models\Report;
use App\Models\User;
use App\Stats\Calculator;
use App\Stats\StatsTransformer;
use App\Transformers\Spreadsheet\ReportTransformer;
use Carbon\Carbon;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Reports
{
    public function process(Report $report)
    {
        $file = $this->generateXls($report);
        $this->sendReport($report, $file);
    }

    public function stats(Report $report, $filterMetrics = true)
    {
        $combineBy = $report->dimension($report->combine_by);
        $sortBy    = $report->dimension($report->sort_by);

        $reportEvents = $this->campaignEvents($report)
            ->filter(function ($event) use ($report) {
                if ($report->sort_by === 'platformType') {
                    return true;
                }

                if (in_array($event->name, ['mobilePageviews', 'desktopPageviews'])) {
                    return false;
                }

                return true;
            })
            ->groupBy(function ($item) use ($combineBy, $sortBy) {
                $groupBy = $item->{$combineBy['model']}->{$combineBy['column']} ?? '';

                if ($combineBy !== $sortBy) {
                    $groupBy .= '-';

                    if ($sortBy['model'] === 'campaign') {
                        $groupBy .= $item->campaign->type->adType->name ?? '';
                    } else {
                        $groupBy .= $item->{$sortBy['model']}->{$sortBy['column']} ?? '';
                    }
                }

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

            // Make sure the combine_by field is always
            // the first item in the stats array
            $stats = $stats->merge([
                $report->combine_by => '',
            ]);

            if ($campaign) {
                $stats = $stats->merge([
                    'ad_type' => $campaign->type->adType->name,
                ]);
            }

            if ($tag && ($combineBy['model'] === 'tag' || $sortBy['model'] === 'tag')) {
                $stats = $stats->merge([
                    'advertiser'    => $tag->advertiser,
                    'description'   => $tag->description,
                    'tag_type'      => $tag->type,
                    'platform_type' => $tag->platform_type,
                ]);
            }

            if ($combineBy['model'] === 'website' || $sortBy['model'] === 'website') {
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
                'ecpm'        => Calculator::ecpm($parsedStats['revenue'], $parsedStats['impressions']),
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
                        Report::$fixedSpreadsheetHeader
                    ));
                });
            }

            $reportStats->push($stats->toArray());
        }

        if ($report->sort_by === 'platformType') {
            $reportStats = $statsTransformer->combineWebsites($reportStats);
        }

        $reportStats = $reportStats->sortBy($report->sort_by);

        dd($reportStats);

        return $reportStats->values();
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
                'date_range' => 'threeDays',
            ],
            [
                'title'      => 'Last 7 Days',
                'date_range' => 'sevenDays',
            ],
            [
                'title'      => 'Last 30 Days',
                'date_range' => 'thirtyDays',
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
    public function campaignEvents(Report $report)
    {
        $stats = CampaignEvent::query()
            ->with('tag', 'website', 'campaign', 'campaign.type')
            ->select('name', 'tag_id', 'campaign_id', 'website_id', 'status', DB::raw('SUM(count) as count'))
            ->groupBy('name', 'tag_id', 'campaign_id', 'website_id', 'status')
            ->where('name', '!=', 'campaignRequests');

        $stats = $report->filterQuery($stats);

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
