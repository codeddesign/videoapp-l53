<?php

namespace App\Models\Traits;

use App\Models\DateRange;

trait Filterable
{
    /**
     * @param $query
     * @param $timeRange
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTimeRange($query, $timeRange)
    {
        $dateRange = DateRange::byName($timeRange);

        return $query->where('created_at', '>=', $dateRange->from)
                     ->where('created_at', '<=', $dateRange->to);
    }
}
