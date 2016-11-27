<?php

namespace App\Services;

use App\Events\CampaignEventReceived;
use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\Models\WordpressSite;
use App\Stats\RedisStats;
use Carbon\Carbon;
use Illuminate\Redis\Database as Redis;
use Illuminate\Support\Collection;

class CampaignEvents
{
    /**
     * @var Redis
     */
    private $redis;

    public function handle($data)
    {
        if (! $data['campaign']) {
            return;
        }

        $userId = $this->getUserForCampaign($data['campaign']);

        $this->broadcastEvent($data, $userId);

        $this->saveOnRedis($data);
    }

    public function fetchAllCampaigns()
    {
        $redis = $this->getRedis();

        $ids = collect($redis->keys('campaign:*'))->map(function ($key) {
            return explode(':', $key)[1];
        })->toArray();

        return $this->fetchMultipleCampaigns($ids);
    }

    public function fetchMultipleCampaigns(array $ids)
    {
        $data = [];

        foreach ($ids as $id) {
            $data[] = $this->fetchStatusForCampaign($id);
        }

        return array_merge_recursive_numeric($data);
    }

    public function fetchStatusForCampaign($campaignId)
    {
        $redisStats = new RedisStats;

        return $redisStats->fetchStatusForCampaign($campaignId);
    }

    public function persistRedisData()
    {
        $redis = $this->getRedis();

        $keys = $redis->keys('campaign:*');

        $events = new Collection();

        foreach ($keys as $key) {
            $id = explode(':', $key)[1];

            $data = $this->fetchStatusForCampaign($id);

            foreach ($data as $event) {
                $events->push($event);
            }

            $redis->del([$key]);
        }

        CampaignEvent::saveMany($events);

        return $events;
    }

    protected function processTags($campaignId, $data, $tags)
    {
        foreach ($data as $tagId => $tagData) {
            foreach ($tagData as $name => $value) {
                $tags->push([
                    'tag_id'      => $tagId,
                    'campaign_id' => $campaignId,
                    'name'        => $name,
                    'count'       => $value,
                ]);
            }
        }

        return $tags;
    }

    /**
     * Broadcasts a new event to a private channel
     * that the user's dashboard is subscribed to
     *
     * @param $data
     * @param $userId
     */
    protected function broadcastEvent($data, $userId)
    {
        $event = new CampaignEventReceived(
            $userId,
            $data['campaign'],
            $data['source'],
            $data['status'],
            Carbon::now(),
            $data['tag']
        );

        broadcast($event);
    }

    /**
     * Save a campaign event in Redis
     *
     * @param $data
     */
    protected function saveOnRedis($data)
    {
        $redis = $this->getRedis();

        $value = "source:{$data['source']}:status:{$data['status']}";

        if (array_get($data, 'tag') !== null) {
            $value .= ":tag:{$data['tag']}";
        }

        if (array_get($data, 'referrer') !== null) {
            $websiteId = WordpressSite::idByLink($data['referrer']);

            if ($websiteId) {
                $value .= ":website:{$websiteId}";
            }
        }

        $redis->hincrby("campaign:{$data['campaign']}", $value, 1);
    }

    /**
     * Fetch the user_id for a given campaign
     * by using a Redis hash map as cache
     *
     * @param $campaignId
     *
     * @return int
     */
    protected function getUserForCampaign($campaignId)
    {
        $redis = $this->getRedis();

        if (is_null($userId = $redis->hget('campaign_user', $campaignId))) {
            $userId = Campaign::find($campaignId)->user_id;
            $redis->hset('campaign_user', $campaignId, $userId);
        }

        return (int) $userId;
    }

    protected function getRedis()
    {
        if (! $this->redis) {
            $this->redis = app(Redis::class);
        }

        return $this->redis->connection();
    }
}
