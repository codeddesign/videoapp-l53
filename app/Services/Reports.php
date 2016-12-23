<?php

namespace App\Services;

use App\Mail\ScheduledReport;
use App\Models\CampaignEvent;
use App\Models\Report;
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
        $stats = CampaignEvent::query()->with('tag');

        $stats = $report->filterQuery($stats);

        return $stats->get();
    }

    /**
     * @param \App\Models\Report $report
     *
     * @return null
     */
    public function generateXls(Report $report)
    {
        $stats = new Collection();
        foreach (range(0, 100) as $item) {
            $stats->push([
                mt_rand(100, 1000),
                mt_rand(100, 1000),
                mt_rand(100, 1000),
            ]);
        }

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
}
