<?php

namespace App\Transformers;

use App\Models\Report;

class ReportTransformer extends Transformer
{
    public function transform(Report $report)
    {
        return [
            'id'       => $report->id,
            'title'    => $report->title,
            'schedule' => $report->schedule,
        ];
    }
}
