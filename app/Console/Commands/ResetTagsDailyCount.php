<?php

namespace App\Console\Commands;

use Illuminate\Redis\RedisManager;

class ResetTagsDailyCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad3:reset-tags-daily-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets all tags daily count';

    /**
     * @var \Illuminate\Redis\RedisManager
     */
    protected $redis;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Redis\RedisManager $redis
     */
    public function __construct(RedisManager $redis)
    {
        parent::__construct();
        $this->redis = $redis;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->redis->del('daily_tag_requests');

        $message = 'Daily tags count was reset successfully.';

        $this->info($message);
        $this->log($message);
    }
}
