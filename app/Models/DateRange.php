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
    public $timezone;

    public function __construct($from, $to, $timezone = 'UTC')
    {
        $this->from     = new Carbon($from, $timezone);
        $this->to       = (new Carbon($to, $timezone))->endOfDay();
        $this->timezone = $timezone;
    }

    public function arrayByStep(CarbonInterval $step = null)
    {
        $step = $step ?: $this->defaultStep();

        $dateRange = new DatePeriod($this->from->tz('UTC'), $step, $this->to->tz('UTC'));

        // Convert the DatePeriod into a plain array of Carbon objects
        $range = new Collection;

        foreach ($dateRange as $period) {
            $date = new Carbon($period);

            $range->push($date);
        }

        return $range;
    }

    public function days()
    {
        return (int) ceil($this->to->diffInHours($this->from) / 24);
    }

    /**
     * @param     $name     Name of the DateRange function
     * @param     $timezone DateRange's timezone
     *
     * @return \App\Models\DateRange
     */
    public static function byName($name, $timezone = null)
    {
        /** @var DateRange $dateRange */
        $dateRange = new self(null, null, $timezone);
        $dateRange->{$name}();

        return $dateRange;
    }

    public function lastTwentyFourHours()
    {
        $this->from = $this->now()->subHours(24)->minute(0)->second(0);
        $this->to   = $this->now()->minute(0)->second(0);

        return $this;
    }

    public function today()
    {
        $this->from = $this->now()->startOfDay();
        $this->to   = $this->now();

        return $this;
    }

    public function yesterday()
    {
        $this->from = $this->now()->subDay()->startOfDay();
        $this->to   = $this->now()->subDay()->endOfDay();

        return $this;
    }

    public function twoDaysAgo()
    {
        $this->from = $this->now()->subDays(2)->startOfDay();
        $this->to   = $this->now()->subDays(2)->endOfDay();

        return $this;
    }

    public function threeDaysAgo()
    {
        $this->from = $this->now()->subDays(3)->startOfDay();
        $this->to   = $this->now()->subDays(3)->endOfDay();

        return $this;
    }

    public function oneWeekAgo()
    {
        $this->from = $this->now()->subWeek()->startOfDay();
        $this->to   = $this->now()->subWeek()->endOfDay();

        return $this;
    }

    /**
     * Last x days implies that "today" is
     * not included in the date range
     *
     * Used for comparisons with today
     */
    public function lastTwoDays()
    {
        $this->from = $this->now()->subDays(2)->startOfDay();
        $this->to   = $this->now()->subDays(1)->endOfDay();

        return $this;
    }

    public function lastThreeDays()
    {
        $this->from = $this->now()->subDays(3)->startOfDay();
        $this->to   = $this->now()->subDays(1)->endOfDay();

        return $this;
    }

    public function lastSevenDays()
    {
        $this->from = $this->now()->subDays(7)->startOfDay();
        $this->to   = $this->now()->subDays(1)->endOfDay();

        return $this;
    }

    public function twoDays()
    {
        $this->from = $this->now()->subDays(1)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function threeDays()
    {
        $this->from = $this->now()->subDays(2)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function sevenDays()
    {
        $this->from = $this->now()->subDays(6)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function thirtyDays()
    {
        $this->from = $this->now()->subDays(29)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function tenDays()
    {
        $this->from = $this->now()->subDays(9)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function thisWeek()
    {
        $this->from = $this->now()->startOfWeek();
        $this->to   = $this->now();

        return $this;
    }

    public function thisMonth()
    {
        $this->from = $this->now()->startOfMonth();
        $this->to   = $this->now();

        return $this;
    }

    public function lastMonth()
    {
        // subMonth() just subtracts 30 days
        // subMonthNoOverflow() actually returns the previous month
        $this->from = $this->now()->subMonthNoOverflow()->startOfMonth();
        $this->to   = $this->now()->subMonthNoOverflow()->endOfMonth();

        return $this;
    }

    protected function now()
    {
        return new Carbon('now', $this->timezone);
    }

    protected function defaultStep()
    {
        return $this->days() > 1 ? CarbonInterval::day() : CarbonInterval::hour();
    }
}
