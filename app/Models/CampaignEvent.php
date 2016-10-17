<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Database Columns
 *
 * @property int       $id
 * @property int       $campaign_id
 * @property string    $name
 * @property string    $event
 * @property string    $referer
 * @property string    $ip
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * Relationships
 *
 * @property Campaign  $campaign
 */
class CampaignEvent extends Model
{
    use SoftDeletes, Filterable;

    /**
     * @var array
     */
    protected $fillable = ['campaign_id', 'name', 'event', 'referer'];

    /**
     * @var array
     */
    protected $hidden = ['ip', 'updated_at', 'deleted_at'];

    /**
     * Set the referer on create.
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($campaignEvent) {
            $campaignEvent->referer = refererUtil();
            $campaignEvent->ip      = ipUtil();
        });
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
     * Fectch the requests, fitlered by a range of time.
     *
     * @param $query
     * @param $request
     *
     * @return Builder
     */
    public function scopeRequests($query, $request)
    {
        return $this->whereHas('campaign', function ($query) use ($query) {
            $query->user_id = auth()->user()->id;
        })->where('name', 'app')->where('event', 'load')->timeRange($request);
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
        return $this->whereHas('campaign', function ($query) use ($query) {
            $query->user_id = auth()->user()->id;
        })->where('name', 'ad')->where('event', 'start')->timeRange($request);
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
        return $this->whereHas('campaign', function ($query) use ($query) {
            $query->user_id = auth()->user()->id;
        })->where('created_at', '>=', Carbon::today()->startOfMonth())
            ->where('name', 'app')
            ->where('event', 'load')
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
        return $this->whereHas('campaign', function ($query) use ($query) {
            $query->user_id = auth()->user()->id;
        })->where('created_at', '>=', Carbon::today()->startOfMonth())
            ->where('name', 'ad')
            ->where('event', 'start')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get([DB::raw('Date(created_at) as date'), DB::raw('count(*) as "impressions"')]);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public static function stats($data)
    {
        set_time_limit(120);

        // range
        $from = (isset($data['from']) && trim($data['from'])) ? $data['from'] : date('Y-m-d');
        $to   = (isset($data['to']) && trim($data['to'])) ? $data['to'] : date('Y-m-d');

        $from = date('Y-m-d H:i:s', strtotime($from.' 00:00:00 -4 hours'));
        $to   = date('Y-m-d H:i:s', strtotime($to.' 23:59:59 -4 hours'));

        $names = self::select('name', 'event')
            ->distinct('name', 'event')
            ->get()
            ->groupBy('name');

        $ignoreFailed = false; //
        foreach ($names as $name => $events) {
            foreach ($events as $index => $event) {
                if ($ignoreFailed && stripos($event->event, 'fail') !== false) {
                    unset($names[$name][$index]);
                    continue;
                }

                $count = self::select(DB::raw('count(*) as total'))
                    ->whereName($name)
                    ->whereEvent($event->event)
                    ->where('created_at', '>=', $from)
                    ->where('created_at', '<=', $to)
                    ->first();

                $names[$name][$index]['total'] = $count->total;
            }

            $names[$name] = array_values($names[$name]->toArray());
        }

        return $names;
    }
}
