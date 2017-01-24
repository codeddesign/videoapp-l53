<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\Reports;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessReport implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new Reports)->process($this->report);
    }
}
