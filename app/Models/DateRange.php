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

        $period = new DatePeriod($this->from, $step, $this->to);

        // Convert the DatePeriod into a plain array of Carbon objects
        $range = new Collection;

        foreach ($period as $day) {
            $range->push(new Carbon($day));
        }

        return $range;
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
