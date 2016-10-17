<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @todo make this dynamic, by reading the method name and translating it to its equivalent filters.
 */
trait Filterable
{
    /**
     * @param $query
     * @param $request
     * @return Builder
     */
    public function scopeTimeRange($query, $request)
    {
        switch ($request->get('time')) {
            case 'today': {
                return $this->today($query);
            }
            case 'yesterday': {
                return $this->today($query);
            }
            case '7-days': {
                return $this->sevenDays($query);
            }
            case 'current-month': {
                return $this->currentMonth($query);
            }
            case 'last-month': {
                return $this->lastMonth($query);
            }
        }

        return $query;
    }

    /**
     * Filter by the events created today.
     *
     * @param $query
     * @return Builder
     */
    public function today($query)
    {
        $today = Carbon::today()->toDateString();

        return $query->where('created_at', '>=', $today);
    }

    /**
     * Filter the events created yesterday.
     *
     * @param $query
     * @return Builder
     */
    public function yesterday($query)
    {
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::today()->subDay()->toDateString();

        return $query->where('created_at', '<=', $today)->where('created_at', '>=', $yesterday);
    }

    /**
     * Filter by the events created the past 7 days.
     *
     * @param $query
     * @return Builder
     */
    public function sevenDays($query)
    {
        $today = Carbon::today()->toDateString();
        $time = Carbon::today()->subWeek()->toDateString();

        return $query->where('created_at', '<=', $today)->where('created_at', '>=', $time);
    }

    /**
     * Filter by the events created this month.
     *
     * @param $query
     * @return Builder
     */
    public function currentMonth($query)
    {
        $today = Carbon::today()->toDateString();
        $time = Carbon::today()->startOfMonth()->toDateString();

        return $query->where('created_at', '<=', $today)->where('created_at', '>=', $time);
    }

    /**
     * Filter by the events created last month.
     *
     * @param $query
     * @return Builder
     */
    public function lastMonth($query)
    {
        $endDate = Carbon::today()->startOfMonth()->toDateString();
        $startDate = Carbon::today()->startOfMonth()->subMonth()->toDateString();

        return $query->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
    }
}
