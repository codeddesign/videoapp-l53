<?php

namespace App\Models;

use App\Models\Traits\SaveMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Database Columns
 *
 * @property int    $id
 * @property string $title
 * @property string $date_range
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $sort_by
 * @property string $combine_by
 * @property string $schedule
 * @property string $schedule_every
 * @property string $recipient
 * @property array  $filter
 * @property array  $included_metrics
 * @property int    $user_id
 * @property bool   $deletable
 * @property Carbon $last_generated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * Relationships
 *
 * @property User   $user
 */
class Report extends Model
{
    use SaveMany;

    protected $fillable = [
        'title', 'date_range', 'sort_by', 'combine_by', 'schedule', 'schedule_every',
        'recipient', 'filter', 'included_metrics', 'deletable',
        'start_date', 'end_date',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'last_generated_at',
    ];

    protected $casts = [
        'filter'           => 'array',
        'included_metrics' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dateRange()
    {
        if ($this->date_range === 'custom') {
            return new DateRange($this->start_date, $this->end_date, $this->user->timezone);
        }

        return DateRange::byName($this->date_range, $this->user->timezone);
    }

    /**
     * @return Collection
     */
    public static function pending()
    {
        $reports = static::all();

        $pendingReports = $reports->filter(function (Report $report) {
            return $report->isPending();
        });

        return $pendingReports;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        // Reports with schedule=once are never pending.
        if ($this->schedule === 'once') {
            return false;
        }

        $now = Carbon::now();

        switch ($this->schedule) {
            case 'daily':
                return $this->last_generated_at === null || $this->last_generated_at->diffInHours($now) >= (24 - 1);
                break;
            case 'weekly':
                return (
                    (
                        $this->last_generated_at === null ||
                        $this->last_generated_at->diffInHours($now) >= ((7 * 24) - 1)
                    ) &&
                    $now->dayOfWeek === (int) $this->schedule_every
                );
                break;
            case 'monthly':
                return $this->monthlyPending();
                break;
        }

        return false;
    }

    protected function monthlyPending()
    {
        $now = Carbon::now();

        // If this report has been generated less than a
        // month ago then we shouldn't send it again.
        if ($this->last_generated_at !== null && $this->last_generated_at->diffInHours($now) <= ((27 * 24) - 1)) {
            return false;
        }

        if ($this->schedule_every === 'beginning') {
            return $now->day === 1;
        }

        if ($this->schedule_every === 'end') {
            return $now->day === $now->daysInMonth;
        }

        return false;
    }

    public static function allMetrics()
    {
        return [
            'ad_type', 'platform_type', 'website', 'impressions', 'unfilled_impressions', 'requests', 'click', 'ctr', 'revenue',
            'cpm', 'fills', 'fill_rate', 'errors', 'error_rate', 'total_tag_type_percent', 'total_platform_type_percent',
            'total_ad_type_percent', 'total_platform_type_errors', 'total_ad_type_errors', 'total_tag_type_errors',
            'start', 'firstquartile', 'midpoint', 'thirdquartile', 'complete', 'average_view_rate', 'average_view_time',
            'completion_rate', 'view_length', 'error101', 'error102', 'error200', 'error201', 'error202', 'error203',
            'error300', 'error301', 'error302', 'error303', 'error400', 'error401', 'error402', 'error403', 'error505',
            'error500', 'error501', 'error502', 'error503', 'error600', 'error601', 'error602', 'error603', 'error604',
            'error900', 'error901',
        ];
    }

    public static function allUserMetrics()
    {
        return [
            'ad_type', 'platform_type', 'website', 'impressions', 'unfilled_impressions', 'click', 'ctr', 'revenue',
            'cpm', 'fills', 'fill_rate', 'start', 'firstquartile', 'midpoint', 'thirdquartile', 'complete',
            'average_view_rate', 'average_view_time', 'completion_rate', 'view_length',
        ];
    }
}
