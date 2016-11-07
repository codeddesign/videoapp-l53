<?php

namespace App\Stats;

use Illuminate\Redis\Database as Redis;

class RedisStats
{
    protected $keys = ['requests', 'impressions', 'fills', 'fillErrors', 'adErrors'];

    protected $redis;

    public function fetchStatusForCampaign($campaignId)
    {
        $redis = $this->getRedis();

        $regex = '/source:(\w*):status:(\d*)(?::tag:(\w*))?(?::website:(\d*))?/';

        $data = $redis->hgetall("campaign:{$campaignId}");

        $stats = $this->addMissingKeys([], $this->keys);

        $stats['tags'] = [];
        $stats['websites'] = [];

        if (count($data) === 0) {
            return $stats;
        }

        foreach ($data as $key => $value) {
            if (preg_match($regex, $key, $matches)) {
                $source  = array_get($matches, 1);
                $status  = (int) array_get($matches, 2);
                $tag     = array_get($matches, 3);
                $website = (int) array_get($matches, 4);

                switch ($source) {
                    case 'app':
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

        foreach ($stats['websites'] as &$website) {
            $website = $this->addMissingKeys($website, $this->keys);
        }

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

    protected function handleAppStats($stats, $value, $status, $tag, $website)
    {
        if ($status == 200) {
            $stats['requests'] += $value;

            if ($website) {
                $this->accumulate($stats['websites'][$website], 'requests', $value);
            }
        }

        return $stats;
    }

    protected function handleTagStats($stats, $value, $status, $tag, $website)
    {
        if ($status == 0) {
            $stats['fills'] += $value;

            if ($website) {
                $this->accumulate($stats['websites'][$website], 'fills', $value);
            }
        } else {
            $stats['fillErrors'] += $value;

            if ($website) {
                $this->accumulate($stats['websites'][$website], 'fillErrors', $value);
            }
        }

        if ($tag) {
            $this->accumulate($stats['tags'][$tag], $status, $value);
        }

        return $stats;
    }

    protected function handleAdStats($stats, $value, $status, $tag, $website)
    {
        if ($status < 100) {
            $stats['impressions'] += $value;

            if ($website) {
                $this->accumulate($stats['websites'][$website], 'impressions', $value);
            }
        } else {
            $stats['adErrors'] += $value;

            if ($website) {
                $this->accumulate($stats['websites'][$website], 'adErrors', $value);
            }
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
