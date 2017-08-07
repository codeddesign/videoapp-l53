<?php

namespace App\Console\Commands;

use App\Jobs\PersistEvents as PersistEventsJob;

class PersistEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad3:persist-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Persist events stored in Redis to the database';

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
        \Log::info('Dispatching PersistEventsJob...');
        dispatch(new PersistEventsJob(uniqid()));
    }
}
