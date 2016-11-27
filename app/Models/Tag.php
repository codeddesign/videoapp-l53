<?php

namespace App\Models;

use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Model;

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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Tag extends Model
{
    use Filterable;

    protected $fillable = [
        'url', 'advertiser', 'description', 'platform_type', 'campaign_types',
        'ad_type', 'date_range', 'start_date', 'end_date', 'daily_request_limit',
        'delay_time', 'ecpm', 'guarantee_limit', 'guarantee_order', 'guarantee_enabled',
        'priority_count', 'timeout_limit', 'wrapper_limit', 'active', 'included_locations',
        'excluded_locations',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'campaign_types'     => 'array',
        'included_locations' => 'array',
        'excluded_locations' => 'array',
    ];

    public static function forLocation(array $location)
    {
        $cache = app(Repository::class);

        // Cache the tags for 5 minutes
        $tags = $cache->remember('tags.all', 5, function () {
            return Tag::all();
        });

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

        return $tags;
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
