<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Database Columns
 *
 * @property int        $id
 * @property string     $title
 * @property string     $date_range
 * @property Carbon     $start_date
 * @property Carbon     $end_date
 * @property string     $sort_by
 * @property string     $schedule
 * @property string     $recipient
 * @property array      $filter
 * @property int        $user_id
 * @property Carbon     $last_generated_at
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 *
 * Relationships
 *
 * @property User       $user
 * @property Collection $jobs
 */
class Report extends Model
{
    protected $fillable = ['title', 'date_range', 'sort_by', 'schedule', 'recipient', 'filter'];

    protected $dates = [
        'start_date',
        'end_date',
        'last_generated_at',
    ];

    protected $casts = [
        'filter' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(ReportJob::class);
    }

    /**
     * @param Builder $query
     *
     * @return $this
     */
    public function filterQuery($query)
    {
        if ($this->filter['value'] === '') {
            return $query;
        }

        $type     = $this->filter['type'];
        $operator = $this->getFilterQueryOperator();
        $value    = $this->getFilterValue();

        // Maps the tag filters to their
        // database column.
        $tagFilters = [
            'advertiser'   => 'advertiser',
            'tagName'      => 'description',
            'platformType' => 'platform_type',
            'adType'       => 'ad_type',
            'campaignType' => 'campaign_type',
        ];

        if (array_key_exists($type, $tagFilters)) {
            return $query->whereHas('tag', function ($query) use ($type, $operator, $value, $tagFilters) {
                $query->where($tagFilters[$type], $operator, $value);
            });
        }

        return $query;
    }

    protected function getFilterQueryOperator()
    {
        $operator = null;

        switch ($this->filter['filter']) {
            case 'doesNotContain':
                $operator = 'not ilike';
                break;
            case 'contains':
                $operator = 'ilike';
                break;
            case 'is':
                $operator = '=';
                break;
            case 'isNot':
                $operator = '!=';
                break;
        }

        return $operator;
    }

    protected function getFilterValue()
    {
        $filter = $this->filter['filter'];
        $value  = $this->filter['value'];

        if ($filter === 'doesNotcontain' || $filter === 'contains') {
            $value = "%{$value}%";
        }

        return $value;
    }

    public function friendlyFilename()
    {
        $now = Carbon::now();

        return "{$now->format('m/d/Y')} - {$this->title}.xlsx";
    }

    public function dateRange()
    {
        if ($this->date_range === 'custom') {
            return new DateRange($this->start_date, $this->end_date);
        }

        return DateRange::byName($this->date_range);
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

        // If there's no last generated report,
        // it must be pending.
        if ($this->last_generated_at === null) {
            return true;
        }

        $now = Carbon::now();

        switch ($this->schedule) {
            case 'daily':
                return $this->last_generated_at->diffInHours($now) >= (24 - 1);
                break;
            case 'weekly':
                return $this->last_generated_at->diffInHours($now) >= ((7 * 24) - 1);
                break;
            case 'monthly':
                return $this->last_generated_at->diffInHours($now) >= ((30 * 24) - 1);
                break;
        }

        return false;
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
}
