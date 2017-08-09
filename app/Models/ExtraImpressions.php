<?php

namespace App\Models;

use App\Models\Traits\SaveMany;
use Carbon\Carbon;
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
class ExtraImpressions extends Model
{
    use SaveMany;

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

        $impressionsData = collect($redis->hgetall('extraimpressions'));

        $impressions = new Collection;

        foreach ($impressionsData as $key => $value) {
            $params = explode(':', $key);

            // $params[2] - if numeric it's a Tag ID, otherwise it's a platform type
            $impressions->push([
                'website_id'   => $params[0],
                'campaign_id'  => $params[1],
                'tag_id'       => is_numeric($params[2]) ? $params[2] : null,
                'session_id'   => $params[3],
                'count'        => $value,
                'platform'     => ! is_numeric($params[2]) ? $params[2] : null,
                'ecpm'         => 0,
                'browser_name' => base64_decode($params[4]),
            ]);
        }

        $tags = Tag::whereIn('id', $impressions->pluck('tag_id')->filter()->unique())->get();

        $impressions = $impressions->map(function ($item) use ($tags) {
            if ($item['tag_id'] === null) {
                return $item;
            }

            $tag = $tags->find($item['tag_id']);

            return array_merge($item, [
                'platform' => $tag->platform_type,
                'ecpm'     => $tag->ecpm,
            ]);
        });

        $impressionsBySession = $impressions->groupBy('session_id')->filter(function ($value, $key) {
            return $key !== '';
        });

        //same session, different UA
        $impressionsBySession->filter(function ($item) {
            return $item->pluck('browser_name')->unique()->count() > 1;
        });

        //UAs w/o impressions
        $userAgentNoImpressions = $impressions->groupBy('browser_name')->filter(function ($item) {
            return $item->sum('count') == 0;
        });

        return $userAgentNoImpressions->map(function ($item) {
            return $item->pluck('website_id')->unique()->values()->toArray();
        });
    }
}
