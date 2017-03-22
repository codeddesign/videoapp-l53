<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreReportRequest;
use App\Jobs\ProcessReport;
use App\Models\Report;
use App\Services\Reports;
use App\Stats\StatsTransformer;
use App\Transformers\ReportTransformer;
use Illuminate\Http\Request;

class ReportsController extends ApiController
{
    public function index()
    {
        $reports = $this->user->reports->sortBy('id');

        return $this->collectionResponse($reports, new ReportTransformer);
    }

    public function stats($id)
    {
        $report = $this->user->reports()->where('id', $id)->firstOrFail();

        $reportsService = new Reports;

        $stats = $reportsService->stats($report, false);

        $campaignEvents = $reportsService->campaignEvents($report);
        $allStats = (new StatsTransformer)->transformSumAll($campaignEvents, true);

        return $this->jsonResponse(compact('allStats', 'stats'));
    }

    public function xls($id)
    {
        $report = $this->user->reports()->where('id', $id)->firstOrFail();

        $reportsService = new Reports;

        $xlsFile = $reportsService->generateXls($report);

        return response()->download($xlsFile, $report->friendlyFilename());
    }

    public function store(StoreReportRequest $request)
    {
        $report = new Report($request->transform());

        $report->user()->associate($this->user);

        $report->save();

        if ($report->schedule === 'once') {
            dispatch(new ProcessReport($report));
        }

        return $this->itemResponse($report, new ReportTransformer);
    }

    public function update($id, StoreReportRequest $request)
    {
        $report = $this->user->reports()->where('id', $id)->firstOrFail();

        $report->update($request->transform());

        if ($report->schedule === 'once') {
            dispatch(new ProcessReport($report));
        }

        return $this->itemResponse($report, new ReportTransformer);
    }

    public function destroy(Request $request)
    {
        $ids = $request->get('reports');

        $this->user->reports()->whereIn('id', $ids)->delete();

        return $this->jsonResponse(['deleted_reports' => $ids]);
    }
}
