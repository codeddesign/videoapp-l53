<?php

namespace VideoAd\Models;

use VideoAd\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\SoftDeletes;
use VideoAd\Http\Controllers\Api\CampaignsController;

/**
 * @author Coded Design
 * Class Campaign
 * @package VideoAd\Models
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
    protected $fillable = ['name', 'user_id', 'campaign_type_id', 'rpm', 'size'];

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
        if (!$this->created_at) {
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
     * @param bool $toSession
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
                    'url' => $url,
                ]);

                if (!$toSession) {
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
     * in case it's some data in preview. Otherwise, if non-zero id
     * is provided it gets it from database.
     *
     * @param int $id
     *
     * @return Campaign|null
     */
    public static function forPlayer($id)
    {
        $campaign = Session::get(CampaignsController::TEMPORARY_PREVIEW_KEY);

        if ($id != 0) {
            $campaign = self::withTrashed()
                ->with('videos')
                ->find($id);
        }

        if (!$campaign) {
            return false;
        };

        $info['type'] = $campaign->type->alias;

        return [
            'campaign' => filterModelKeys(
                $campaign->toArray(),
                ['id', 'name', 'size', 'url', 'source']
            ),
            'info' => filterModelKeys(
                $info,
                ['type', 'available', 'single', 'has_name']
            ),
            'tags' => env_adTags(),
        ];
    }
}
