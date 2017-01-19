<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Redis\Database as Redis;

/**
 * Database Columns
 *
 * @property int    $id
 * @property string $url
 * @property string $advertiser
 * @property string $description
 * @property string $platform_type
 * @property array  $campaign_types
 * @property string $ad_type
 * @property bool   $date_range
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property int    $daily_request_limit
 * @property int    $delay_time
 * @property int    $ecpm
 * @property int    $guarantee_limit
 * @property int    $guarantee_order
 * @property bool   $guarantee_enabled
 * @property int    $priority_count
 * @property int    $timeout_limit
 * @property int    $wrapper_limit
 * @property bool   $active
 * @property array  $included_locations
 * @property array  $excluded_locations
 * @property array  $included_websites
 * @property array  $excluded_websites
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Tag extends Model
{
    use Filterable, SoftDeletes;

    protected $fillable = [
        'url', 'advertiser', 'description', 'platform_type', 'campaign_types',
        'ad_type', 'date_range', 'start_date', 'end_date', 'daily_request_limit',
        'delay_time', 'ecpm', 'guarantee_limit', 'guarantee_order', 'guarantee_enabled',
        'priority_count', 'timeout_limit', 'wrapper_limit', 'active', 'included_locations',
        'excluded_locations', 'included_websites', 'excluded_websites',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at',
    ];

    protected $casts = [
        'campaign_types'     => 'array',
        'included_locations' => 'array',
        'excluded_locations' => 'array',
        'included_websites'  => 'array',
        'excluded_websites'  => 'array',
    ];

    public static function forRequest(array $location, $referer)
    {
        /** @var Repository $cache */
        $cache = app(Repository::class);

        // Cache the tags
        $tags = $cache->tags(['tags'])->remember('tags.all', 5, function () {
            return Tag::where('active', true)
                ->where(function ($query) {
                    $date = Carbon::now();

                    return $query->where('date_range', false)
                        ->orWhere('start_date', '<=', $date)
                        ->where('end_date', '>=', $date);
                })->get();
        });

        $websiteId = WordpressSite::idByLink($referer);

        $tags = $cache->tags(['tags'])->remember("tags.website.{$websiteId}", 60, function () use ($tags, $websiteId) {
            return $tags->filter(function ($tag) use ($websiteId) {
                // If there's no targeting, all websites are allowed
                if (count($tag->included_websites) === 0 && count($tag->excluded_websites) === 0) {
                    return true;
                }

                if (count($tag->excluded_websites) > 0) {
                    if (in_array($websiteId, $tag->excluded_websites)) {
                        return false;
                    }
                }

                if (count($tag->included_websites) > 0) {
                    return in_array($websiteId, $tag->included_websites);
                }
            });
        });

        // Filter locations
        $tags = $tags->filter(function (self $tag) use ($location) {
            // If there's no targeting, all locations are allowed
            if (count($tag->included_locations) === 0 && count($tag->excluded_locations) === 0) {
                return true;
            }

            // If the location is excluded, filter the tag out
            if (count($tag->excluded_locations) > 0) {
                foreach ($tag->excluded_locations as $excludedLocation) {
                    if (static::compareLocations($location, $excludedLocation) === true) {
                        return false;
                    }
                }
            }

            // If there are include locations, the user must be within them
            if (count($tag->included_locations) > 0) {
                foreach ($tag->included_locations as $includedLocation) {
                    if (static::compareLocations($location, $includedLocation) === true) {
                        return true;
                    }
                }

                return false;
            }

            // The user location is not excluded and
            // there are not included locations
            return true;
        });

        foreach ($tags as $tag) {
            $tag->guaranteed_count = $tag->requestCount();
        }

        return $tags;
    }

    public function requestCount()
    {
        $redis = app(Redis::class);

        $redisRequests = $redis->hget('tag_requests', $this->id);

        $databaseRequests = CampaignEvent::where('tag_id', $this->id)->where('name', 'requests')->sum('count');

        return $redisRequests + $databaseRequests;
    }

    public static function compareLocations($userLocation, $location)
    {
        return (
            $userLocation['country'] === $location['country']
            && (
                ! isset($location['state']) || $location['state'] === $userLocation['state']
            )
            && (
                ! isset($location['city']) || $location['city'] === $userLocation['city']
            )
        );
    }
}
