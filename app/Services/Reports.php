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

    public function stats(Report $report)
    {
        $events = $this->campaignEvents($report)->groupBy(function ($item) {
            return $item->tag_id;
        });

        $stats = new Collection;
        $statsTransformer = new StatsTransformer;

        foreach ($events as $tagEvents) {
            $parsedStats = $statsTransformer->transformSumAll($tagEvents);

            $tag = $tagEvents->first()->tag;

            $tagStats = [
                'advertiser'    => $tag->advertiser,
                'description'   => $tag->description,
                'ad_type'       => $tag->ad_type,
                'platform_type' => $tag->platform_type,
                'impressions'   => $parsedStats['impressions'],
                'requests'      => $parsedStats['requests'],
                'ecpm'          => $tag->ecpm / 100.0,
                'fills'         => $parsedStats['fills'],
                'revenue'       => $parsedStats['revenue'],
                'errors'        => $parsedStats['fillErrors'] + $parsedStats['adErrors'],
            ];

            $stats->push($tagStats);
        }

        return $stats;
    }

    /**
     * @param \App\Models\Report $report
     *
     * @return null
     */
    public function generateXls(Report $report)
    {
        $stats = $this->stats($report);

        $extraHeaderRows = [
            ['Report Name', $report->title],
            ['Generated At', Carbon::now()->format('F j, Y g:i A e')],
            ['Date Range', "{$report->dateRange()->from->toFormattedDateString()} - {$report->dateRange()->to->toFormattedDateString()}"],
        ];

        $spreadsheet = new Spreadsheet;

        $file = $spreadsheet->xlsxFile($stats, new ReportTransformer, $extraHeaderRows);

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
}
