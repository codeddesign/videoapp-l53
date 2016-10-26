<?php

namespace App\Services;

use App\Events\CampaignEventReceived;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Redis\Database as Redis;

class CampaignEvents
{
    /**
     * @var Redis
     */
    private $redis;

    public function handle($data)
    {
        $userId = $this->getUserForCampaign($data['campaign']);

        $this->broadcastEvent($data, $userId);

        $this->saveOnRedis($data);
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

        if ($data['tag']) {
            $tagBase64 = base64_encode($data['tag']);
            $value .= ":tag:{$tagBase64}";
        }

        $redis->hincrby("campaign:{$data['campaign']}", $value, 1);
    }

    /**
     * Fetch the user_id for a given campaign by
     * leveraging Redis cache using an hash map
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
