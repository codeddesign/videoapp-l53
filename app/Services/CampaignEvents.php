<?php

namespace App\Services;

use App\Models\Backfill;
use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\Models\Tag;
use App\Models\Website;
use App\Stats\RedisStats;
use Carbon\Carbon;
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

        $tagIds      = $data->pluck('tag_id')->unique()->values();
        $backfillIds = $data->pluck('backfill_id')->unique()->values();

        $tags      = Tag::whereIn('id', $tagIds)->get();
        $backfills = Backfill::whereIn('id', $backfillIds)->get();

        $data = $data->map(function ($event) use ($tags, $backfills) {
            if (isset($event->tag_id)) {
                $event->tag = $tags->find($event->tag_id);
            }

            if (isset($event->backfill_id)) {
                $event->backfill = $backfills->find($event->backfill_id);
            }

            return $event;
        });

        return $data;
    }

    public function persistRedisData()
    {
        $redis = $this->getRedis();

        $keys = $redis->keys('campaign:*');

        \Log::info($keys);

        $events = new Collection();

        $redisStats = new RedisStats;

        $validIds = [
            'campaigns' => Campaign::all()->pluck('id'),
            'websites'  => Website::all()->pluck('id'),
            'tags'      => Tag::all()->pluck('id'),
            'backfill'  => Backfill::all()->pluck('id'),
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

        $timestamp = Carbon::now()->second(0)->subSecond();

        CampaignEvent::saveMany($events, $timestamp);

        return $events;
    }

    protected function validIds($event, $validIds)
    {
        return $this->validId($event['campaign_id'], $validIds['campaigns']) &&
            $this->validId($event['tag_id'], $validIds['tags']) &&
            $this->validId($event['backfill_id'] ?? null, $validIds['backfill']) &&
            $this->validId($event['website_id'], $validIds['websites']);
    }

    protected function validId($id, Collection $validIds)
    {
        if ($id === null) {
            return true;
        }

        if (! is_numeric($id)) {
            return false;
        }

        if (! $validIds->contains($id)) {
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
