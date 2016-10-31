<?php

namespace App\Services;

use App\Events\CampaignEventReceived;
use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\User;
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
        $userId = $this->getUserForCampaign($data['campaign']);

        $this->broadcastEvent($data, $userId);

        $this->saveOnRedis($data);
    }

    public function fetchUserCampaignsFromRedis(User $user)
    {
        $campaignIds = $user->campaigns->pluck('id');

        $requests    = 0;
        $impressions = 0;
        $fills       = 0;
        $fillErrors  = 0;
        $adErrors    = 0;

        foreach ($campaignIds as $id) {
            $data = $this->fetchStatusForCampaign($id);
            $requests += $data['requests'];
            $impressions += $data['impressions'];
            $fills += $data['fills'];
            $fillErrors += $data['fillErrors'];
            $adErrors += $data['adErrors'];
        }

        return [
            'requests'    => $requests,
            'impressions' => $impressions,
            'fills'       => $fills,
            'fillErrors'  => $fillErrors,
            'adErrors'    => $adErrors,
        ];
    }

    public function fetchStatusForCampaign($campaignId)
    {
        $redis = $this->getRedis();

        $data = $redis->hgetall("campaign:{$campaignId}");
        if (count($data) === 0) {
            return;
        }

        $requests    = 0;
        $impressions = 0;
        $fills       = 0;
        $fillErrors  = 0;
        $adErrors    = 0;
        $tags        = [];

        foreach ($data as $key => $value) {
            // any tag status
            if (preg_match('/source:tag:status:([0-9]*)(?::tag:(.*))?/', $key, $matches)) {
                $status = $matches[1];
                if ($status == 0) {
                    $fills += $value;
                } else {
                    $fillErrors += $value;
                }

                //tag is set
                if (count($matches) > 2) {
                    $tag = base64_decode($matches[2]);
                    if (! array_key_exists($tag, $tags)) {
                        $tags[$tag] = [];
                    }
                    $tags[$tag][$status] = $value;
                }
                continue;
            }

            // any ad status >= 100 is an ad error
            if (preg_match('/(source:ad:status:[0-9]{3}.*)/', $key)) {
                $adErrors += $value;
                continue;
            }
        }

        $requests += $data['source:app:status:200'];
        $impressions += $data['source:ad:status:0'];

        return [
            'requests'    => $requests,
            'impressions' => $impressions,
            'fills'       => $fills,
            'adErrors'    => $adErrors,
            'fillErrors'  => $fillErrors,
            'tags'        => $tags,
        ];
    }

    public function persistRedisData()
    {
        $redis = $this->getRedis();

        $keys = $redis->keys('campaign:*');

        $events = new Collection();

        foreach ($keys as $key) {
            $id = explode(':', $key)[1];

            $data = $this->fetchStatusForCampaign($id);

            foreach ($data as $key => $value) {
                if ($key === 'tags') {
                    //TODO: save tag events separately
                    continue;
                }

                $events->push([
                    'campaign_id' => $id,
                    'name'        => $key,
                    'count'       => $value,
                ]);
            }

            CampaignEvent::saveMany($events);
        }

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

        if ($data['tag']) {
            $tagBase64 = base64_encode($data['tag']);
            $value .= ":tag:{$tagBase64}";
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
