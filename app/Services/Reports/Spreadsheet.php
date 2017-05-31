<?php

namespace App\Services\Reports;

use App\Mail\ScheduledReport;
use App\Models\CampaignEvent;
use App\Models\Report;
use App\Transformers\Spreadsheet\ReportTransformer;
use Carbon\Carbon;
use Illuminate\Mail\Mailer;
use App\Services\Spreadsheet as BaseSpreadsheet;

class Spreadsheet
{
    public function process(Report $report)
    {
        $file = $this->generateXls($report);
        $this->sendReport($report, $file);
    }

    /**
     * @param \App\Models\Report $report
     *
     * @param bool               $update
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function generateXls(Report $report, $update = true)
    {
        $stats = collect((new Reports)->stats($report));

        $headerRows = [
            ['Report Name', $report->title],
            ['Generated At', Carbon::now()->format('F j, Y g:i A e')],
            ['Date Range', "{$report->dateRange()->from->toFormattedDateString()} - {$report->dateRange()->to->toFormattedDateString()}"],
            $this->header($stats),
        ];

        $spreadsheet = new BaseSpreadsheet;

        $file = $spreadsheet->xlsxFile($stats, new ReportTransformer, $headerRows);

        if ($update) {
            $report->last_generated_at = Carbon::now();
            $report->save();
        }

        return $file;
    }

    public function sendReport(Report $report, $file)
    {
        /** @var Mailer $mailer */
        $mailer = app(Mailer::class);

        $recipients = collect(explode(',', $report->recipient));
        $recipients->map(function ($email) {
            return trim($email);
        });

        $mailer->to($recipients)->send(new ScheduledReport($report, $file));
    }

    public function friendlyFilename(Report $report)
    {
        $now = Carbon::now();

        $name = preg_replace("([^\w\s\d\.\-_~,;:\[\]\(\)]|[\.]{2,})", '', $report->title);

        return "{$now->format('mdY')} - {$name}.xlsx";
    }

    public function header($stats)
    {
        $header = collect([
            'advertiser'       => 'Advertiser',
            'description'      => 'Description',
            'ad_type'          => 'Ad Type',
            'platform_type'    => 'Platform Type',
            'website'          => 'Website',
            'desktopPageviews' => 'Desktop Pageviews',
            'mobilePageviews'  => 'Mobile Pageviews',
            'requests'         => 'Ad Requests',
            'impressions'      => 'Impressions',
            'fills'            => 'Fills',
            'fill_rate'        => 'Fill Rate',
            'pv_fill_rate'     => 'PV Fill Rate',
            'revenue'          => 'Revenue',
            'cpm'              => 'eCPM',
            'click'            => 'Clicks',
            'start'            => 'Start',
            'firstquartile'    => 'First Quartile',
            'midpoint'         => 'Midpoint',
            'thirdquartile'    => 'Third Quartile',
            'complete'         => 'Completed',
            'ctr'              => 'CTR',
            'completion_rate'  => 'Completion Rate',
            'errors'           => 'Total Errors',
            'error_rate'       => 'Error Rate',
        ]);

        $errorCodes = CampaignEvent::$errors;
        $errors     = [];

        foreach ($errorCodes as $code) {
            $errors["error{$code}"] = "Error {$code}";
        }

        $header = $header->merge($errors);

        $orderedHeader = [];

        if (count($stats) === 0) {
            return $header->values()->toArray();
        }

        foreach ($stats->first() as $key => $value) {
            $orderedHeader[] = $header->get($key) ?? $key;
        }

        return $orderedHeader;
    }
}
