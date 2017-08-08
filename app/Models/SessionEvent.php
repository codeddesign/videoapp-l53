<?php

namespace App\Models;

use App\Models\Traits\SaveMany;
use App\Models\Traits\TimeRange;
use App\Sessions\RedisSessions;
use Carbon\Carbon;

/**
 * Database Columns
 *
 * @property int      $id
 * @property int      $campaign_id
 * @property int      $website_id
 * @property string   $platform
 * @property int      $ecpm
 * @property int      $sessions
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * Relationships
 *
 * @property Campaign $campaign
 */
class SessionEvent extends Model
{
    use SaveMany, TimeRange;

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public static function persist()
    {
        $redisSessions = new RedisSessions;
        $impressionsByCampaign = $redisSessions->fetch();
        $redisSessions->delete();

        $timestamp = Carbon::now()->second(0)->subSecond();

        return static::saveMany($impressionsByCampaign, $timestamp);
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
        });
    }
}
