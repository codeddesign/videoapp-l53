<?php

namespace App\Geolite;

use Illuminate\Console\Command;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad3:import-geolite
    {--update : Update geolite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import IP ranges and locations from storage (.csv)';

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
        $update = $this->option('update');

        $import = new Import($update);

        if (Location::first() && ! $update) {
            $this->comment('Geolite table looks already seeded. Skipping for now..');

            return false;
        }

        $this->info('Creating IP ranges..');
        $import->ranges();

        $this->info('Creating IP locations..');
        $import->locations();
    }
}
