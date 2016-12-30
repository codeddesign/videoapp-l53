<?php

namespace App\Transformers\Spreadsheet;

class ReportTransformer
{
    public function header()
    {
        return [
            'Advertiser',
            'Description',
            'Requests',
            'Impressions',
            'Fills',
            'Revenue',
        ];
    }

    public function transform($stat)
    {
        return $stat;
    }
}
