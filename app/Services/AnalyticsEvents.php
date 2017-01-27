<?php

namespace App\Services;

use App\Models\CampaignEvent;
use App\Stats\RedisStats;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Collection;

class AnalyticsEvents
{
    /**
     * @var Redis
     */
    private $redis;

    public function persistRedisData()
    {
        $redis = $this->getRedis();

        $keys = $redis->keys('website:*');

        $events = new Collection();

        foreach ($keys as $key) {
            $id = explode(':', $key)[1];

            $data = $this->fetchAnalyticsForWebsite($id);

            foreach ($data as $event) {
                $events->push($event);
            }

            $redis->del([$key]);
        }

        CampaignEvent::saveMany($events);

        return $events;
    }

    public function fetchAllAnalytics()
    {
        $redis = $this->getRedis();

        $websitesKey = 'website:*';

        $ids = collect($redis->keys($websitesKey))->map(function ($key) {
            return explode(':', $key)[1];
        })->toArray();

        return $this->fetchMultipleAnalytics($ids);
    }

    public function fetchMultipleAnalytics(array $ids)
    {
        $data = new Collection();

        foreach ($ids as $id) {
            $this->fetchAnalyticsForWebsite($id)->map(function ($stat) use ($data) {
                $data->push((object) $stat);
            });
        }

        return $data;
    }

    public function fetchAnalyticsForWebsite($websiteId)
    {
        $redisStats = new RedisStats;

        return $redisStats->fetchStatusForWebsite($websiteId);
    }

    protected function getRedis()
    {
        if (! $this->redis) {
            $this->redis = app(RedisManager::class);
        }

        return $this->redis->connection();
    }
}
