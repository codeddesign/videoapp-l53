<?php

namespace App\Models;

use App\Models\Traits\SaveMany;
use App\Models\Traits\TimeRange;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Database Columns
 *
 * @property int      $id
 * @property int      $campaign_id
 * @property int      $tag_id
 * @property int      $website_id
 * @property int      $backfill_id
 * @property string   $name
 * @property int      $count
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * Relationships
 *
 * @property Campaign $campaign
 */
class CampaignEvent extends Model
{
    use SoftDeletes, TimeRange, SaveMany;

    /**
     * @var array
     */
    protected $fillable = ['campaign_id', 'name', 'count'];

    /**
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at'];

    public static $errors = [101, 102, 200, 201, 202, 203, 300, 301, 302, 303, 400, 401, 402, 403, 405, 500, 501, 502, 503, 600, 601, 602, 603, 604, 900, 901];

    public static $viewership = [
        1  => 'loaded',
        2  => 'start',
        4  => 'firstquartile',
        5  => 'midpoint',
        6  => 'thirdquartile',
        7  => 'complete',
        9  => 'pause',
        14 => 'click',
    ];

    public function backfill()
    {
        return $this->belongsTo(Backfill::class);
    }

    /**
     * A campaign event belongs to a campaign.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    /**
     * A campaign event belongs to a tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id')->withTrashed();
    }

    /**
     * A campaign event belongs to a website.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function scopeUserStats($query, $user = null)
    {
        if ($user === null) {
            $user = auth()->user();
        }

        if ($user->admin) {
            return $query;
        }

        return $query->where(function ($query) use ($user) {
            return $query->whereHas('campaign', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereHas('website', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })->orWhereHas('website', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }
}
