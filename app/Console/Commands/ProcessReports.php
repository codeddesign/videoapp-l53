<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Services\Reports\Spreadsheet;

class ProcessReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad3:process-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and send pending reports';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = microtime(true);

        $pendingReports = Report::pending();

        $this->info("Processing {$pendingReports->count()} pending reports.");

        $bar = $this->output->createProgressBar($pendingReports->count());

        $reportsService = new Spreadsheet;

        $pendingReports->each(function ($report) use ($reportsService, $bar) {
            $reportsService->process($report);
            $bar->advance();
        });

        $bar->finish();
        $timeElapsed = number_format(microtime(true) - $start, 2);

        $this->line(''); // New line after the progress bar
        $this->info("All done. {$pendingReports->count()} reports sent in {$timeElapsed} seconds.");

        $this->log("{$pendingReports->count()} reports sent in {$timeElapsed} seconds.");
    }
}
