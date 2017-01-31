<?php

namespace App\Console;

use App\Console\Commands\CleanTemporaryFiles;
use App\Console\Commands\ClearAllEvents;
use App\Console\Commands\PersistEvents;
use App\Console\Commands\ProcessReports;
use App\Geolite\ImportCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PersistEvents::class,
        ImportCommand::class,
        ProcessReports::class,
        CleanTemporaryFiles::class,
        ClearAllEvents::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ad3:persist-events')->hourly();
        $schedule->command('ad3:process-reports')->daily();
        $schedule->command('ad3:clean-temporary-files')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
