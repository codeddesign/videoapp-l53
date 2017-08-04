<?php

namespace App\Models;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Collection;

/**
 * Database Columns
 *
 * @property int      $id
 * @property int      $campaign_id
 * @property int      $website_id
 * @property string   $platform
 * @property int      $ecpm
 * @property int      $sessions
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * Relationships
 *
 * @property Campaign $campaign
 */
class Session extends Model
{
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public static function persist()
    {
        $redis = app(RedisManager::class)->connection();

        $impressions = collect($redis->hgetall('impressions'));

        $impressionsBySession = new Collection;

        foreach ($impressions as $key => $value) {
            $params = explode(':', $key);

            $impressionsBySession->push([
                'website_id'  => $params[0],
                'campaign_id' => $params[1],
                'tag_id'      => $params[2],
                'session_id'  => $params[3],
                'count'       => $value,
            ]);
        }

        $tags = Tag::whereIn('id', $impressionsBySession->pluck('tag_id')->unique())->get();

        $impressionsBySession = $impressionsBySession->map(function ($item) use ($tags) {
            $tag = $tags->find($item['tag_id']);

            return array_merge($item, [
                'ecpm'     => $tag->ecpm,
                'platform' => $tag->platform_type,
            ]);
        });

        $impressionsByCampaign = $impressionsBySession->groupBy(function ($item) {
            return $item['website_id'].$item['campaign_id'].$item['platform'];
        });

        return $impressionsByCampaign->map(function ($item) {
            return [
                'website_id'  => $item[0]['website_id'],
                'campaign_id' => $item[0]['campaign_id'],
                'platform'    => $item[0]['platform'],
                'ecpm'        => $item->sum(function ($item) {
                    return $item['ecpm'] * $item['count'];
                }),
                'sessions'    => $item->pluck('session_id')->unique()->count(),
            ];
        });
    }
}
