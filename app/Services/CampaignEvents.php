<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\Models\Tag;
use App\Models\Website;
use App\Stats\RedisStats;
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

        $validIds = [
            'campaigns' => Campaign::all()->pluck('id'),
            'websites'  => Website::all()->pluck('id'),
            'tags'      => Tag::all()->pluck('id'),
        ];

        foreach ($keys as $key) {
            $id = explode(':', $key)[1];

            $data = $redisStats->fetchStatusForCampaign($id);

            foreach ($data as $event) {
                if ($this->validIds($event, $validIds)) {
                    $events->push($event);
                } else {
                    \Log::info('Tried persisting invalid event: '.json_encode($event));
                }
            }

            $redis->del([$key]);
        }

        CampaignEvent::saveMany($events);

        return $events;
    }

    protected function validIds($event, $validIds)
    {
        return $this->validId($event['campaign_id'], $validIds['campaigns']) &&
            $this->validId($event['tag_id'], $validIds['tags']) &&
            $this->validId($event['website_id'], $validIds['websites']);
    }

    protected function validId($id, Collection $validIds)
    {
        if ($id !== null && ! $validIds->contains($id)) {
            return false;
        }

        return true;
    }

    protected function getRedis()
    {
        if (! $this->redis) {
            $this->redis = app(RedisManager::class);
        }

        return $this->redis->connection();
    }
}
