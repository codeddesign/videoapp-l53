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
            'start_date'       => $this->dateTime($report->start_date),
            'end_date'         => $this->dateTime($report->end_date),
            'filter'           => $report->filter,
            'included_metrics' => $report->included_metrics,
            'sort_by'          => $report->sort_by,
            'combine_by'       => $report->combine_by,
            'schedule'         => $report->schedule,
            'schedule_every'   => $report->schedule_every,
            'recipient'        => $report->recipient,
            'deletable'        => $report->deletable,
        ];

        return $transformedReport;
    }
}
