<?php

namespace App\Stats;

use Illuminate\Redis\Database as Redis;
use Illuminate\Support\Collection;

class RedisStats
{
    protected $keys = ['requests', 'impressions', 'fills', 'fillErrors', 'adErrors'];

    protected $redis;

    public function fetchStatusForCampaign($campaignId, $daily = false)
    {
        $redis = $this->getRedis();

        $campaignKey = "campaign:{$campaignId}";

        if ($daily) {
            $campaignKey = "daily-{$campaignKey}";
        }

        $regex = '/source:(\w*):status:(\d*)(?::tag:(\w*))?(?::website:(\d*))?/';

        $data = $redis->hgetall($campaignKey);

        $stats = new Collection();

        if (count($data) === 0) {
            return $stats;
        }

        foreach ($data as $key => $value) {
            if (preg_match($regex, $key, $matches)) {
                $source  = array_get($matches, 1);
                $status  = (int) array_get($matches, 2);
                $tag     = array_get($matches, 3) ? intval(array_get($matches, 3)) : null;
                $website = array_get($matches, 4) ? intval(array_get($matches, 4)) : null;

                switch ($source) {
                    case 'campaign':
                        $stats = $this->handleAppStats($stats, $value, $status, $tag, $website);
                        break;

                    case 'tag':
                        $stats = $this->handleTagStats($stats, $value, $status, $tag, $website);
                        break;

                    case 'ad':
                        $stats = $this->handleAdStats($stats, $value, $status, $tag, $website);
                        break;
                }
            }
        }

        //attach the campaign id to all events
        $stats = $stats->map(function ($event) use ($campaignId) {
            $event['campaign_id'] = $campaignId;

            return $event;
        });

        return $stats;
    }

    protected function addMissingKeys($array, $keys, $default = 0)
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $array)) {
                $array[$key] = 0;
            }
        }

        return $array;
    }

    protected function handleAppStats(Collection $stats, $value, $status, $tag = null, $website = null)
    {
        if ($status == 200) {
            $stats->push([
                'name'       => 'requests',
                'status'     => $status,
                'count'      => $value,
                'tag_id'     => $tag,
                'website_id' => $website,
            ]);
        }

        return $stats;
    }

    protected function handleTagStats($stats, $value, $status, $tag, $website)
    {
        if ($status == 200) {
            $stats->push([
                'name'       => 'fills',
                'status'     => $status,
                'count'      => $value,
                'tag_id'     => $tag,
                'website_id' => $website,
            ]);
        } else {
            $stats->push([
                'name'       => 'fillErrors',
                'status'     => $status,
                'count'      => $value,
                'tag_id'     => $tag,
                'website_id' => $website,
            ]);
        }

        return $stats;
    }

    protected function handleAdStats($stats, $value, $status, $tag, $website)
    {
        if ($status === 3) {
            $stats->push([
                'name'       => 'impressions',
                'status'     => $status,
                'count'      => $value,
                'tag_id'     => $tag,
                'website_id' => $website,
            ]);
        } elseif ($status < 100) {
            $stats->push([
                'name'       => 'viewership',
                'status'     => $status,
                'count'      => $value,
                'tag_id'     => $tag,
                'website_id' => $website,
            ]);
        } else {
            $stats->push([
                'name'       => 'adErrors',
                'status'     => $status,
                'count'      => $value,
                'tag_id'     => $tag,
                'website_id' => $website,
            ]);
        }

        return $stats;
    }

    protected function accumulate(&$array, $key, $value, $default = 0)
    {
        if (is_null($array)) {
            $array = [];
        }

        if (! array_key_exists($key, $array)) {
            $array[$key] = $default;
        }

        $array[$key] += $value;
    }

    protected function getRedis()
    {
        if (! $this->redis) {
            $this->redis = app(Redis::class);
        }

        return $this->redis->connection();
    }
}
