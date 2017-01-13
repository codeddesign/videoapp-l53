<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Database Columns
 *
 * @property int     $id
 * @property int     $user_id
 * @property string  $domain
 * @property bool $approved
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 *
 * Relationships
 *
 * @property User    $user
 *
 * Accessors
 *
 * @property string  $link
 */
class WordpressSite extends Model
{
    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'wordpress_sites';

    /**
     * The allowed fields.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'domain'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['link'];

    /**
     * A wordpress site belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param string $link
     */
    public function setDomainAttribute($link)
    {
        $this->attributes['domain'] = self::linkDomain($link);
    }

    /**
     * @return string
     */
    public function getLinkAttribute()
    {
        return 'http://'.$this->domain;
    }

    /**
     * @param string $link
     *
     * @return string
     */
    protected static function linkDomain($link)
    {
        $parsed = parse_url($link);
        if (! isset($parsed['host'])) {
            return false;
        }

        return str_replace('www.', '', strtolower($parsed['host']));
    }

    /**
     * @param string $link
     *
     * @return null|WordpressSite
     */
    public static function byLink($link)
    {
        return self::whereDomain(self::linkDomain($link))->first();
    }

    /**
     * @param $link
     *
     * @return int|null Website ID
     */
    public static function idByLink($link)
    {
        /** @var Client $redis */
        $redis = app('redis')->connection();

        $domain = self::linkDomain($link);

        if (! $domain) {
            return;
        }

        if (is_null($websiteId = $redis->hget('domain_website', $domain))) {
            $website = self::whereDomain($domain)->first();

            if (! $website) {
                return;
            }

            $redis->hset('domain_website', $domain, $website->id);
        }

        return (int) $websiteId;
    }
}
