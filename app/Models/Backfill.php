<?php

namespace App\Models;

use Illuminate\Cache\Repository;

/**
 * Database Columns
 *
 * @property int      $id
 * @property int      $website_id
 * @property int      $ad_type_id
 * @property string   $embed
 * @property string   $advertiser
 * @property string   $platform_type
 * @property string   $width
 * @property int      $ecpm
 * @property bool  $active
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * Relationships
 *
 * @property Website  $website
 * @property AdType $adType
 */
class Backfill extends Model
{
    protected $table = 'backfill';

    protected $fillable = ['advertiser', 'embed', 'ad_type_id', 'platform_type', 'width', 'ecpm', 'website_id'];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function adType()
    {
        return $this->belongsTo(AdType::class, 'ad_type_id');
    }

    public static function forRequest($website, $type, $platform)
    {
        /** @var Repository $cache */
        $cache = app(Repository::class);

        return self::where('active', true)
            ->where('website_id', $website->id ?? null)
            ->where('ad_type_id', $type)
            ->where('platform_type', $platform)
            ->first();
    }
}
