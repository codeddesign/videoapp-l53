<?php

namespace App\Models;

use App\Transformers\CampaignTypeTransformer;
use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * Database Columns
 *
 * @property int          $id
 * @property int          $user_id
 * @property int          $campaign_type_id
 * @property int          $rpm
 * @property int          $size
 * @property string       $name
 * @property bool         $active
 * @property Carbon       $created_at
 * @property Carbon       $updated_at
 *
 * Relationships
 *
 * @property User         $user
 * @property CampaignType $type
 * @property Collection   $videos
 * @property Collection   $campaignEvents
 *
 * Accessors
 *
 * @property string       $created_at_humans
 */
class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'campaigns';

    /**
     * The allowed field.
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id', 'campaign_type_id', 'rpm', 'size', 'closeable'];

    /**
     * @var array
     */
    protected $appends = ['created_at_humans'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rpm' => 'integer',
    ];

    /**
     * Humans readable time.
     * We handle missing created_at when is temporary/on-session.
     */
    public function getCreatedAtHumansAttribute()
    {
        if (! $this->created_at) {
            return '1 second ago';
        }

        return $this->created_at->diffForHumans();
    }

    /**
     * A campaign belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A campaign belongs to a campaign type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(CampaignType::class, 'campaign_type_id');
    }

    /**
     * A campaign may have many videos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * A campaign can have many events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaignEvents()
    {
        return $this->hasMany(CampaignEvent::class);
    }

    /**
     * @param array $data
     * @param bool  $toSession
     *
     * @return array
     */
    public function addVideos(array $data, $toSession = false)
    {
        $videos = Video::videosFromData($data);

        $list = [];
        foreach ($videos as $url) {
            if (trim($url)) {
                $video = new Video();
                $video->fill([
                    'campaign_id' => $this->id,
                    'url'         => $url,
                ]);

                if (! $toSession) {
                    $video->save();
                }

                $list[] = $video->toArray();
            }
        }

        return $list;
    }

    /**
     * Returns campaign details and information about campaign's type.
     * First it makes and attempt to fetch campaign data from session,
     *  in case it's some data in preview. Otherwise, if non-zero id
     *  is provided it gets it from database.
     *
     * @param int $id
     *
     * @return Campaign|null
     */
    public static function forPlayer($id)
    {
        $previewKey = config('videoad.TEMPORARY_PREVIEW_KEY');
        $cache      = app(Repository::class);

        if ($id != 0) {
            $campaign = $cache->tags(['campaigns'])->remember("campaigns.{$id}", 5, function () use ($id) {
                return self::where('active', true)
                    ->whereHas('user', function ($query) {
                        $query->where('active', true);
                    })
                    ->where('id', $id)
                    ->first();
            });
        } else {
            $previewId          = app('request')->cookie('preview_id');
            $campaignSerialized = app('redis')->connection()->get("{$previewKey}.{$previewId}");
            $campaign           = unserialize($campaignSerialized);
        }

        if (! $campaign) {
            return false;
        }

        $info         = (new CampaignTypeTransformer)->transform($campaign->type);
        $info['type'] = $campaign->type->alias;

        return [
            'id'      => $campaign->id,
            'ad_type' => $campaign->type->adType->id,
            'closeable' => $campaign->closeable,
        ];
    }

    public function embedCode()
    {
        $script = '<div class="a3m-row"><script async src="http://cdn.a3m.io/i%d.js" styling="bottom:0,right:0,width:320"></script></div>';

        return sprintf($script, $this->id);
    }
}
