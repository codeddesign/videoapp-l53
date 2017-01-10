<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Support\Collection;

class CleanTemporaryFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad3:clean-temporary-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans old reports and other temporary files';

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
        $storage       = app(Filesystem::class)->disk('local');
        $now           = Carbon::now();
        $filesToDelete = new Collection;

        $files = $storage->allFiles('tmp');

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($storage->lastModified($file));

            if ($now->diffInHours($lastModified) > 24) {
                $filesToDelete->push($file);
            }
        }

        $storage->delete($filesToDelete->toArray());

        $message = "{$filesToDelete->count()} files deleted.";

        $this->info($message);
        $this->log($message);
    }
}
