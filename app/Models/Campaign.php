<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Coded Design
 *
 * @package App\Models
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
}
