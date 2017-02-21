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
        $this->to       = new Carbon($to, $timezone);
        $this->timezone = $timezone;
    }

    public function arrayByStep(CarbonInterval $step = null)
    {
        $step = $step ?: $this->defaultStep();

        $dateRange = new DatePeriod($this->from, $step, $this->to);

        // Convert the DatePeriod into a plain array of Carbon objects
        $range = new Collection;

        foreach ($dateRange as $period) {
            $date = new Carbon($period);

            //if the time is delayed, use the next hour
            if ($date->second !== 0) {
                $date->addHour()->second(0);
            }

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
     * @param int $delay    How many seconds to delay the dates.
     *                      Defaults to 30 as the data is usually saved
     *                      less than 30 seconds into the next hour.
     *
     * @return \App\Models\DateRange
     */
    public static function byName($name, $timezone = null, $delay = 30)
    {
        /** @var DateRange $dateRange */
        $dateRange = new self(null, null, $timezone);
        $dateRange->{$name}();

        if ($delay !== 0) {
            $dateRange->from->addSeconds($delay);
            $dateRange->to->addSeconds($delay);
        }

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
        $this->from = $this->now()->subDays(2)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function threeDays()
    {
        $this->from = $this->now()->subDays(3)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function sevenDays()
    {
        $this->from = $this->now()->subDays(7)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function thirtyDays()
    {
        $this->from = $this->now()->subDays(30)->startOfDay();
        $this->to   = $this->now()->endOfDay();

        return $this;
    }

    public function tenDays()
    {
        $this->from = $this->now()->startOfDay();
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
