<?php

namespace App\Transformers\Spreadsheet;

use App\Models\CampaignEvent;
use App\Models\Report;

class ReportTransformer
{
    public function header(Report $report)
    {
        return $report->spreadsheetHeader();
    }

    public function transform($stat)
    {
        return $stat;
    }

    protected function errorsHeader()
    {
        $errors = collect(CampaignEvent::$errors);

        return $errors->map(function ($error) {
            return "Error {$error}";
        })->toArray();
    }
}
