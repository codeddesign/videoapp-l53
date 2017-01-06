<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\SaveMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Database Columns
 *
 * @property int      $id
 * @property int      $campaign_id
 * @property int      $tag_id
 * @property int      $website_id
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
    use SoftDeletes, Filterable, SaveMany;

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
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    /**
     * A campaign event belongs to a website.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function website()
    {
        return $this->belongsTo(WordpressSite::class, 'website_id');
    }

    public function scopeUserStats($query, $timeRange)
    {
        return $this->whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->timeRange($timeRange);
    }

    /**
     * Fetch the requests, filtered by a range of time.
     *
     * @param $query
     * @param $request
     *
     * @return Builder
     */
    public function scopeRequests($query, $request)
    {
        return $this->whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('name', 'requests')->timeRange($request);
        // timeRange: is found in App\Models\Filterable trait as a query scope.
    }

    /**
     * Fetch the impressions, filtered by a range of time.
     *
     * @param $query
     * @param $request
     *
     * @return Builder
     */
    public function scopeImpressions($query, $request)
    {
        return $this->whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('name', 'impressions')->timeRange($request);
        // timeRange: is found in App\Models\Filterable trait as a query scope.
    }

    /**
     * return the daily count of the requests per month.
     *
     * @param $query
     *
     * @return Collection
     */
    public function scopeRequestsStats($query)
    {
        return $this->whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('created_at', '>=', Carbon::today()->startOfMonth())
            ->where('name', 'requests')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([DB::raw('Date(created_at) as date'), DB::raw('count(*) as "requests"')]);
    }

    /**
     * Return the daily count of the impressions per month.
     *
     * @param $query
     *
     * @return Collection
     */
    public function scopeImpressionsStats($query)
    {
        return $this->whereHas('campaign', function ($query) {
            $query->user_id = auth()->user()->id;
        })->where('created_at', '>=', Carbon::today()->startOfMonth())
            ->where('name', 'impressions')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([DB::raw('Date(created_at) as date'), DB::raw('count(*) as "impressions"')]);
    }
}
