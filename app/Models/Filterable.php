<?php

namespace App\Models;

/**
 * @todo make this dynamic, by reading the method name and translating it to its equivalent filters.
 */
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
        $dateRange = call_user_func(DateRange::class.'::'.$timeRange);

        return $query->where('created_at', '>=', $dateRange->from)
                     ->where('created_at', '<=', $dateRange->to);
    }
}
