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

            foreach ($data as $name => $value) {
                if ($name === 'tags') {
                    //TODO: save tag events separately
                    continue;
                }

                $events->push([
                    'campaign_id' => $id,
                    'name'        => $name,
                    'count'       => $value,
                ]);
            }

            var_dump('Deleting Redis key: '.$key);
            $redis->del([$key]);
        }

        CampaignEvent::saveMany($events);

        return $events;
    }

    /**
     * Broadcasts a new event to a private channel
     * that the dashboard is subscribed to
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
     * @param $data
     */
    protected function saveOnRedis($data)
    {
        $redis = $this->getRedis();

        $value = "source:{$data['source']}:status:{$data['status']}";

        if (array_get($data, 'tag') !== null) {
            $tagBase64 = base64_encode($data['tag']);
            $value .= ":tag:{$tagBase64}";
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
     * Fetch the user_id for a given campaign by
     * leveraging Redis as a cache using an hash map
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
