<?php

namespace App\Models\Traits;

use App\Models\DateRange;

trait Filterable
{
    /**
     * @param      $query
     * @param      $timeRange
     *
     * @param null $delay
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTimeRange($query, $timeRange, $delay = null)
    {
        $dateRange = DateRange::byName($timeRange, $delay);

        return $query->where('created_at', '>=', $dateRange->from)
                     ->where('created_at', '<=', $dateRange->to);
    }
}
