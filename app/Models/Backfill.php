<?php

namespace App\Models;

use Illuminate\Cache\Repository;

/**
 * Database Columns
 *
 * @property int     $id
 * @property int     $website_id
 * @property string  $embed
 * @property string  $advertiser
 * @property string  $ad_type
 * @property string  $platform_type
 * @property string  $width
 * @property int     $ecpm
 * @property boolean $active
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 *
 * Relationships
 *
 * @property Website $website
 */
class Backfill extends Model
{
    protected $table = 'backfill';

    protected $fillable = ['advertiser', 'embed', 'ad_type', 'platform_type', 'width', 'ecpm', 'website_id'];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public static function forRequest($websiteId, $type, $platform)
    {
        /** @var Repository $cache */
        $cache = app(Repository::class);

        return Backfill::where('active', true)
            ->where('website_id', $websiteId)
            ->where('ad_type', $type)
            ->where('platform_type', $platform)
            ->first();
    }
}
