<?php

namespace App\Services;

use App\Stats\RedisStats;
use Illuminate\Redis\Database as Redis;
use Illuminate\Support\Collection;

class AnalyticsEvents
{
    /**
     * @var Redis
     */
    private $redis;

    public function fetchAllAnalytics($daily = false)
    {
        $redis = $this->getRedis();

        $websitesKey = 'website:*';

        if ($daily) {
            $websitesKey = "daily-{$websitesKey}";
        }

        $ids = collect($redis->keys($websitesKey))->map(function ($key) {
            return explode(':', $key)[1];
        })->toArray();

        return $this->fetchMultipleAnalytics($ids, $daily);
    }

    public function fetchMultipleAnalytics(array $ids, $daily = false)
    {
        $data = new Collection();

        foreach ($ids as $id) {
            $data->offsetSet($id, $this->fetchAnalyticsForWebsite($id, $daily));
        }

        return $data;
    }

    public function fetchAnalyticsForWebsite($websiteId, $daily = false)
    {
        $redisStats = new RedisStats;

        return $redisStats->fetchStatusForWebsite($websiteId, $daily);
    }

    protected function getRedis()
    {
        if (! $this->redis) {
            $this->redis = app(Redis::class);
        }

        return $this->redis->connection();
    }
}
