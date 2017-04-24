<?php

namespace App\Stats;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Collection;

class RedisStats
{
    protected $keys = ['requests', 'impressions', 'fills', 'errors'];

    protected $redis;

    public function fetchStatusForWebsite($websiteId)
    {
        $redis = $this->getRedis();

        $websiteKey = "website:{$websiteId}";

        $data = $redis->hgetall($websiteKey);

        $stats = new Collection;

        $stats->push([
            'name'       => 'mobilePageviews',
            'status'     => 0,
            'count'      => (int) array_get($data, 'platform:mobile', 0),
            'website_id' => $websiteId,
        ]);

        $stats->push([
            'name'       => 'desktopPageviews',
            'status'     => 0,
            'count'      => (int) array_get($data, 'platform:desktop', 0),
            'website_id' => $websiteId,
        ]);

        return $stats;
    }

    public function fetchStatusForCampaign($campaignId)
    {
        $redis = $this->getRedis();

        $campaignKey = "campaign:{$campaignId}";

        $regex = '/source:(\w*)(?::status:(\d*))?(?::tag:(\w*))?(?::website:(\d*))?(?::backfill:(\d*))?/';

        $data = $redis->hgetall($campaignKey);

        $stats = new Collection();

        if (count($data) === 0) {
            return $stats;
        }

        foreach ($data as $key => $value) {
            if (preg_match($regex, $key, $matches)) {
                $source   = array_get($matches, 1);
                $status   = (int) array_get($matches, 2);
                $tag      = array_get($matches, 3) ? intval(array_get($matches, 3)) : null;
                $website  = array_get($matches, 4) ? intval(array_get($matches, 4)) : null;
                $backfill = array_get($matches, 5) ? intval(array_get($matches, 5)) : null;

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

                    case 'backfill':
                        $stats = $this->handleBackfillStats($stats, $value, $status, $tag, $website, $backfill);
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
        $stats->push([
            'name'        => 'campaignRequests',
            'status'      => $status,
            'count'       => $value,
            'tag_id'      => $tag,
            'website_id'  => $website,
            'backfill_id' => null,
        ]);

        return $stats;
    }

    protected function handleTagStats($stats, $value, $status, $tag, $website)
    {
        $stats->push([
            'name'        => 'tagRequests',
            'status'      => $status,
            'count'       => $value,
            'tag_id'      => $tag,
            'website_id'  => $website,
            'backfill_id' => null,
        ]);

        return $stats;
    }

    protected function handleAdStats($stats, $value, $status, $tag, $website)
    {
        if ($status === 0) {
            $stats->push([
                'name'        => 'fills',
                'status'      => $status,
                'count'       => $value,
                'tag_id'      => $tag,
                'website_id'  => $website,
                'backfill_id' => null,
            ]);
        } elseif ($status === 3) {
            $stats->push([
                'name'        => 'impressions',
                'status'      => $status,
                'count'       => $value,
                'tag_id'      => $tag,
                'website_id'  => $website,
                'backfill_id' => null,
            ]);
        } elseif ($status < 100) {
            $stats->push([
                'name'        => 'viewership',
                'status'      => $status,
                'count'       => $value,
                'tag_id'      => $tag,
                'website_id'  => $website,
                'backfill_id' => null,
            ]);
        } else {
            $stats->push([
                'name'        => 'errors',
                'status'      => $status,
                'count'       => $value,
                'tag_id'      => $tag,
                'website_id'  => $website,
                'backfill_id' => null,
            ]);
        }

        return $stats;
    }

    protected function handleBackfillStats($stats, $value, $status, $tag, $website, $backfill)
    {
        $stats->push([
            'name'        => 'backfill',
            'status'      => null,
            'count'       => $value,
            'tag_id'      => $tag,
            'website_id'  => $website,
            'backfill_id' => $backfill,
        ]);

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
            $this->redis = app(RedisManager::class);
        }

        return $this->redis->connection();
    }
}
