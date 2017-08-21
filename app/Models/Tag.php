<?php

namespace App\Models;

use App\Models\Traits\TimeRange;
use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Redis\RedisManager;

/**
 * Database Columns
 *
 * @property int    $id
 * @property string $url
 * @property string $advertiser
 * @property string $description
 * @property string $platform_type
 * @property array  $ad_types
 * @property string $type
 * @property bool   $date_range
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property int    $daily_request_limit
 * @property int    $ecpm
 * @property int    $guarantee_limit
 * @property int    $guarantee_order
 * @property bool   $guarantee_enabled
 * @property int    $priority_count
 * @property int    $timeout_limit
 * @property int    $wrapper_limit
 * @property int    $delay_time
 * @property int    $infinity_timeout_limit
 * @property int    $infinity_wrapper_limit
 * @property int    $infinity_delay_time
 * @property bool   $active
 * @property bool   $for_owned
 * @property array  $included_locations
 * @property array  $excluded_locations
 * @property array  $included_websites
 * @property array  $excluded_websites
 * @property int    $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Tag extends Model
{
    use TimeRange, SoftDeletes;

    protected $fillable = [
        'url', 'advertiser', 'description', 'platform_type', 'ad_types',
        'type', 'date_range', 'start_date', 'end_date', 'daily_request_limit',
        'delay_time', 'ecpm', 'guarantee_limit', 'guarantee_order', 'guarantee_enabled',
        'priority_count', 'timeout_limit', 'wrapper_limit', 'active', 'included_locations',
        'excluded_locations', 'included_websites', 'excluded_websites', 'infinity_timeout_limit',
        'infinity_wrapper_limit', 'infinity_delay_time', 'demo_data', 'for_owned',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at',
    ];

    protected $casts = [
        'ad_types'           => 'array',
        'included_locations' => 'array',
        'excluded_locations' => 'array',
        'included_websites'  => 'array',
        'excluded_websites'  => 'array',
        'demo_data'          => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function forRequest(array $location, $website)
    {
        /** @var Repository $cache */
        $cache = app(Repository::class);

        // Cache the tags
        $tags = $cache->tags(['tags'])->remember('tags.all', 5, function () use ($website) {
            return Tag::where('active', true)
                ->where(function ($query) {
                    $date = Carbon::now();

                    return $query->where('date_range', false)
                        ->orWhere('start_date', '<=', $date)
                        ->where('end_date', '>=', $date);
                })->get();
        });

        $tags = $cache->tags(['tags'])->remember("tags.website.{$website->id}", 60, function () use ($tags, $website) {
            return $tags->filter(function ($tag) use ($website) {
                if ($website->owned !== $tag->for_owned) {
                    return false;
                }

                // If there's no targeting, all websites are allowed
                if (count($tag->included_websites) === 0 && count($tag->excluded_websites) === 0) {
                    return true;
                }

                if (count($tag->excluded_websites) > 0) {
                    if (in_array($website->id, $tag->excluded_websites)) {
                        return false;
                    }
                }

                if (count($tag->included_websites) > 0) {
                    return in_array($website->id, $tag->included_websites);
                }

                return true;
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

        //Filter user tags
        $tags = $tags->filter(function (self $tag) use ($website) {
            if ($tag->user_id === null) {
                return true;
            }

            return $tag->user_id === $website->user_id;
        });

        foreach ($tags as $tag) {
            $requests = $tag->requestCount();

            $tag->guaranteed_count = $requests['total'];
            $tag->daily_count      = $requests['daily'];
        }

        return $tags;
    }

    public function requestCount()
    {
        $redis = app(RedisManager::class);

        $totalRequests = (int) $redis->hget('tag_requests', $this->id) ?? 0;
        $dailyRequests = (int) $redis->hget('daily_tag_requests', $this->id) ?? 0;

        return [
            'total' => $totalRequests,
            'daily' => $dailyRequests,
        ];
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
