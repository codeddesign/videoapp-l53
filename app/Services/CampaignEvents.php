<?php

namespace App\Services;

use App\Events\CampaignEventReceived;
use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\Models\Tag;
use App\Models\Website;
use App\Stats\RedisStats;
use Carbon\Carbon;
use Illuminate\Log\Writer as Log;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Collection;

class CampaignEvents
{
    /**
     * @var Redis
     */
    private $redis;

    public function fetchAllCampaigns()
    {
        $redis = $this->getRedis();

        $campaignsKey = 'campaign:*';

        $ids = collect($redis->keys($campaignsKey))->map(function ($key) {
            return explode(':', $key)[1];
        })->toArray();

        return $this->fetchMultipleCampaigns($ids);
    }

    public function fetchMultipleCampaigns(array $ids)
    {
        $data = new Collection();

        $redisStats = new RedisStats;

        foreach ($ids as $id) {
            $redisStats->fetchStatusForCampaign($id)->map(function ($stat) use ($data) {
                $data->push((object) $stat);
            });
        }

        $tagIds = $data->pluck('tag_id')->unique()->values();

        $tags = Tag::whereIn('id', $tagIds)->get();

        $data = $data->map(function ($event) use ($tags) {
            if ($event->tag_id) {
                $event->tag = $tags->find($event->tag_id);
            }

            return $event;
        });

        return $data;
    }

    public function persistRedisData()
    {
        $redis = $this->getRedis();

        $keys = $redis->keys('campaign:*');

        $events = new Collection();

        $redisStats = new RedisStats;

        foreach ($keys as $key) {
            $id = explode(':', $key)[1];

            $data = $redisStats->fetchStatusForCampaign($id);

            foreach ($data as $event) {
                $events->push($event);
            }

            $redis->del([$key]);
        }

        CampaignEvent::saveMany($events);

        return $events;
    }

    protected function getRedis()
    {
        if (! $this->redis) {
            $this->redis = app(RedisManager::class);
        }

        return $this->redis->connection();
    }
}
