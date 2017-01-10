<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreReportRequest;
use App\Models\Report;
use App\Services\Reports;
use App\Transformers\ReportTransformer;
use Illuminate\Http\Request;

class ReportsController extends ApiController
{
    public function index()
    {
        $reports = $this->user->reports;

        return $this->collectionResponse($reports, new ReportTransformer);
    }

    public function stats($id)
    {
        $report = $this->user->reports()->where('id', $id)->firstOrFail();

        $reportsService = new Reports;

        $tagStats = $reportsService->stats($report, false);

        $allStats = [
            'requests'    => 0,
            'impressions' => 0,
            'fills'       => 0,
            'ad_errors'   => 0,
        ];

        foreach ($tagStats as $tag) {
            $allStats['requests'] += $tag['requests'];
            $allStats['impressions'] += $tag['impressions'];
            $allStats['fills'] += $tag['fills'];
            $allStats['ad_errors'] += $tag['errors'];
        }

        return $this->jsonResponse(compact('allStats', 'tagStats'));
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

        if($report->schedule === 'once') {
            (new Reports)->process($report);
        }

        return $this->itemResponse($report, new ReportTransformer);
    }

    public function update($id, StoreReportRequest $request)
    {
        $report = $this->user->reports()->where('id', $id)->firstOrFail();

        $report->update($request->transform());

        if($report->schedule === 'once') {
            (new Reports)->process($report);
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