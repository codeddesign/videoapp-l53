<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Youtube;

/**
 * Database Columns
 *
 * @property int      $id
 * @property int      $campaign_id
 * @property string   $url
 * @property string   $source
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * Relationships
 *
 * @property Campaign $campaign
 */
class Video extends Model
{
    use SoftDeletes;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'videos';

    /**
     * The allowed fields.
     *
     * @var array
     */
    protected $fillable = ['url', 'source', 'campaign_id'];

    /**
     * A video belongs to a campaign.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    /**
     * Parse given youtube link and save only it's id.
     *
     * @param string $url
     */
    public function setUrlAttribute($url)
    {
        if (stripos($url, 'youtube') !== false) {
            $url = Youtube::id($url);
        }

        $this->attributes['url'] = $url;
    }

    /**
     *  Videos can be either 'directly' or 'comming from a request'.
     *
     *  If directly it means that it expects it to be a simple
     *  array with values that represent video urls.
     *
     *  If it's comming from a request it expects the video/s
     *  to be in a subkey called ['video'] or ['videos'] when
     *  there are multiple ones added by user.
     *
     * @param array $data
     *
     * @return array
     */
    public static function videosFromData($data)
    {
        $videos = $data;

        if (isset($data['video'])) {
            $videos = [$data['video']];
        }

        if (isset($data['videos']) and count($data['videos'])) {
            $videos = $data['videos'];
        }

        return $videos;
    }
}
