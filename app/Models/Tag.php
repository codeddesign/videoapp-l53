<?php

namespace App\Models;

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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Tag extends Model
{
    protected $fillable = [
        'url', 'advertiser', 'description', 'platform_type', 'campaign_types',
        'ad_type', 'date_range', 'start_date', 'end_date', 'daily_request_limit',
        'delay_time', 'ecpm', 'guarantee_limit', 'guarantee_order', 'guarantee_enabled',
        'priority_count', 'timeout_limit', 'wrapper_limit', 'active',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'campaign_types' => 'array',
    ];
}
