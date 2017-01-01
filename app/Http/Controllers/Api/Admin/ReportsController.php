<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreReportRequest;
use App\Models\Report;
use App\Services\Reports;
use App\Transformers\ReportTransformer;

class ReportsController extends ApiController
{
    public function index()
    {
        $reports = $this->user->reports;

        return $this->collectionResponse($reports, new ReportTransformer);
    }

    public function stats($id)
    {
        $report = Report::where('id', $id)->where('user_id', $this->user->id)->first();

        $reportsService = new Reports;

        $tagStats = $reportsService->stats($report);


        return $this->itemResponse($report, new ReportTransformer);
    }

    public function store(StoreReportRequest $request)
    {
        $report = new Report($request->transform());

        $report->user()->associate($this->user);

        $report->save();

        return $this->itemResponse($report, new ReportTransformer);
    }
}
