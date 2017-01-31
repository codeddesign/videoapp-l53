<?php

namespace App\Models\Traits;

use App\Models\DateRange;

trait TimeRange
{
    /**
     * @param        $query
     * @param        $timeRange
     * @param string $timezone
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTimeRange($query, $timeRange, $timezone = 'UTC')
    {
        if (! $timeRange instanceof DateRange) {
            $timeRange = DateRange::byName($timeRange, $timezone);
        }

        return $query->where('created_at', '>=', $timeRange->from->tz('UTC'))
            ->where('created_at', '<=', $timeRange->to->tz('UTC'));
    }
}
