<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\Reports\Spreadsheet as ReportsSpreadsheet;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\Report
     */
    protected $report;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Report $report
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
        parent::__construct();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->lockJob()) {
            return;
        }

        (new ReportsSpreadsheet)->process($this->report);
    }
}
