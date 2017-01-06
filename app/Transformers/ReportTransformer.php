<?php

namespace App\Transformers;

use App\Models\Report;

class ReportTransformer extends Transformer
{
    public function transform(Report $report)
    {
        $transformedReport = [
            'id'               => $report->id,
            'title'            => $report->title,
            'date_range'       => $report->date_range,
            'filter'           => $report->filter,
            'included_metrics' => $report->included_metrics,
            'sort_by'          => $report->sort_by,
            'schedule'         => $report->schedule,
            'recipient'        => $report->recipient,
        ];

        return $transformedReport;
    }
}
