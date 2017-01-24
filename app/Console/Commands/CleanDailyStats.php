<?php

namespace App\Console\Commands;

use Illuminate\Redis\Database as Redis;

class CleanDailyStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad3:clean-daily-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the daily stats stored on Redis';

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
        $this->info('Cleaning Redis daily stats...');

        $redis        = app(Redis::class);
        $campaignKeys = $redis->keys('daily-campaign:*');
        $websitesKeys = $redis->keys('daily-website:*');

        $keys = array_merge($campaignKeys, $websitesKeys);

        $redis->del($keys);

        $message = 'Deleted '.count($keys).' Redis daily stats.';

        $this->info($message);
        $this->log($message);
    }
}
