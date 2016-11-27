<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Support\Collection;

class DateRange
{
    public $from;
    public $to;

    public function __construct(Carbon $from, Carbon $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function arrayByStep(CarbonInterval $step = null)
    {
        $step = $step ?: $this->defaultStep();

        $dateRange = new DatePeriod($this->from, $step, $this->to);

        // Convert the DatePeriod into a plain array of Carbon objects
        $range = new Collection;

        foreach ($dateRange as $period) {
            $range->push(new Carbon($period));
        }

        return $range;
    }

    public function days()
    {
        return (int) ceil($this->to->diffInHours($this->from) / 24);
    }

    public static function today()
    {
        $from = Carbon::now()->startOfDay();
        $to   = Carbon::now();

        return new static($from, $to);
    }

    public static function yesterday()
    {
        $from = Carbon::now()->subDay()->startOfDay();
        $to   = Carbon::now()->subDay()->endOfDay();

        return new static($from, $to);
    }

    public static function twoDaysAgo()
    {
        $from = Carbon::now()->subDays(2)->startOfDay();
        $to   = Carbon::now()->subDays(2)->endOfDay();

        return new static($from, $to);
    }

    public static function threeDaysAgo()
    {
        $from = Carbon::now()->subDays(3)->startOfDay();
        $to   = Carbon::now()->subDays(3)->endOfDay();

        return new static($from, $to);
    }

    public static function oneWeekAgo()
    {
        $from = Carbon::now()->subWeek()->startOfDay();
        $to   = Carbon::now()->subWeek()->endOfDay();

        return new static($from, $to);
    }

    /**
     * Last x days implies that "today" is
     * not included in the date range
     *
     * Used for comparisons with today
     */
    public static function lastTwoDays()
    {
        $from = Carbon::now()->subDays(2)->startOfDay();
        $to   = Carbon::now()->subDays(1)->endOfDay();

        return new static($from, $to);
    }

    public static function lastThreeDays()
    {
        $from = Carbon::now()->subDays(3)->startOfDay();
        $to   = Carbon::now()->subDays(1)->endOfDay();

        return new static($from, $to);
    }

    public static function lastSevenDays()
    {
        $from = Carbon::now()->subDays(7)->startOfDay();
        $to   = Carbon::now()->subDays(1)->endOfDay();

        return new static($from, $to);
    }

    public static function twoDays()
    {
        $from = Carbon::now()->subDays(2)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        return new static($from, $to);
    }

    public static function threeDays()
    {
        $from = Carbon::now()->subDays(3)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        return new static($from, $to);
    }

    public static function sevenDays()
    {
        $from = Carbon::now()->subDays(7)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        return new static($from, $to);
    }

    public static function tenDays()
    {
        $from = Carbon::now()->subDays(10)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        return new static($from, $to);
    }

    public static function thisMonth()
    {
        $from = Carbon::now()->startOfMonth();
        $to   = Carbon::now();

        return new static($from, $to);
    }

    public static function lastMonth()
    {
        // subMonth() just subtracts 30 days
        // subMonthNoOverflow() actually returns the previous month
        $from = Carbon::now()->subMonthNoOverflow()->startOfMonth();
        $to   = Carbon::now()->subMonthNoOverflow()->endOfMonth();

        return new static($from, $to);
    }

    protected function defaultStep()
    {
        return CarbonInterval::day();
    }
}
