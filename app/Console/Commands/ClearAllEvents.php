<?php

namespace App\Console\Commands;

use App\Models\CampaignEvent;
use Illuminate\Redis\RedisManager;
use Illuminate\Database\Connection as Database;

class ClearAllEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad3:clear-all-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all Redis and Postgres events.';

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $redis;

    /**
     * @var \Illuminate\Database\Connection
     */
    protected $database;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Redis\RedisManager  $redis
     * @param \Illuminate\Database\Connection $database
     */
    public function __construct(RedisManager $redis, Database $database)
    {
        parent::__construct();
        $this->redis    = $redis->connection();
        $this->database = $database;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->confirm('This command will delete ALL data. Do you wish to continue?')) {
            return;
        }

        $this->clearRedisEvents();
        $this->clearDatabaseEvents();
    }

    protected function clearDatabaseEvents()
    {
        $events = CampaignEvent::all()->count();
        $this->database->table((new CampaignEvent)->getTable())->truncate();
        $this->info("Deleted {$events} campaign events.");
    }

    protected function clearRedisEvents()
    {
        $campaignKeys = $this->redis->keys('campaign:*');
        $websitesKeys = $this->redis->keys('website:*');
        $keys         = array_merge($campaignKeys, $websitesKeys);

        if (count($keys) > 0) {
            $this->redis->del($keys);
        }

        $this->info('Deleted '.count($keys).' Redis keys.');
    }
}
