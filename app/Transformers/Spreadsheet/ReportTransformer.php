<?php

namespace App\Transformers\Spreadsheet;

class ReportTransformer
{
    public function header()
    {
        return [
            'Tag Name',
            'Tag ID',
            'Total Impressions',
        ];
    }

    public function transform($stat)
    {
        return $stat;
    }
}
