<?php

namespace App\Models;

use App\Models\Traits\SaveMany;
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
 * @property string     $schedule_every
 * @property string     $recipient
 * @property array      $filter
 * @property array      $included_metrics
 * @property int        $user_id
 * @property bool    $deletable
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
    use SaveMany;

    public static $fixedSpreadsheetHeader = ['advertiser', 'description'];

    protected $fillable = [
        'title', 'date_range', 'sort_by', 'schedule', 'schedule_every',
        'recipient', 'filter', 'included_metrics', 'deletable',
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

    public function jobs()
    {
        return $this->hasMany(ReportJob::class);
    }

    public function spreadsheetHeader($stats)
    {
        $header = collect([
            'advertiser'      => 'Advertiser',
            'description'     => 'Description',
            'ad_type'         => 'Ad Type',
            'platform_type'   => 'Platform Type',
            'website'         => 'Website',
            'requests'        => 'Ad Requests',
            'impressions'     => 'Impressions',
            'fills'           => 'Fills',
            'fill_rate'       => 'Fill Rate',
            'revenue'         => 'Revenue',
            'ecpm'            => 'eCPM',
            'click'           => 'Clicks',
            'start'           => 'Start',
            'firstquartile'   => 'First Quartile',
            'midpoint'        => 'Midpoint',
            'thirdquartile'   => 'Third Quartile',
            'complete'        => 'Completed',
            'ctr'             => 'CTR',
            'completion_rate' => 'Completion Rate',
            'errors'          => 'Total Errors',
            'error_rate'      => 'Error Rate',
        ]);

        $errorCodes = CampaignEvent::$errors;
        $errors     = [];

        foreach ($errorCodes as $code) {
            $errors["error{$code}"] = "Error {$code}";
        }

        $header = $header->merge($errors);

        $orderedHeader = [];

        if (count($stats) === 0) {
            return $header->values()->toArray();
        }

        foreach ($stats[0] as $key => $value) {
            $orderedHeader[] = $header->get($key) ?? $key;
        }

        return $orderedHeader;
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
            'campaignType' => 'campaign_types',
        ];

        // campaign_type is json, so it should be handled
        // with the appropriate PG json functions.
        if ($type === 'campaignType') {
            return $query->whereHas('tag', function ($query) use ($type, $operator, $value, $tagFilters) {
                $query->whereRaw("{$operator}({$tagFilters[$type]}, {$value})");
            });
        }

        if (array_key_exists($type, $tagFilters)) {
            return $query->whereHas('tag', function ($query) use ($type, $operator, $value, $tagFilters) {
                $query->where($tagFilters[$type], $operator, $value);
            });
        }

        return $query;
    }

    protected function getFilterQueryOperator()
    {
        $filter = $this->filter['filter'];

        if ($this->filter['type'] === 'campaignType' && $filter === 'contains') {
            return 'jsonb_exists_any';
        } else {
            if ($this->filter['type'] === 'campaignType' && $filter === 'is') {
                return 'jsonb_eq';
            }
        }

        $operator = null;

        switch ($filter) {
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

        // the campaign_types column is json encoded
        if ($this->filter['type'] === 'campaignType' && $filter === 'contains') {
            return "array['{$value}']";
        } else {
            if ($this->filter['type'] === 'campaignType' && $filter === 'is') {
                return "to_jsonb(array['{$value}'])";
            }
        }

        if ($filter === 'doesNotcontain' || $filter === 'contains') {
            $value = "%{$value}%";
        }

        return $value;
    }

    public function friendlyFilename()
    {
        $now = Carbon::now();

        return "{$now->format('mdY')} - {$this->title}.xlsx";
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
            'cpm', 'fills', 'fill_rate', 'errors', 'error_rate', 'total_ad_type_percent', 'total_platform_type_percent',
            'total_campaign_type_percent', 'total_platform_type_errors', 'total_ad_type_errors', 'total_campaign_type_errors',
            'start', 'firstquartile', 'midpoint', 'thirdquartile', 'complete', 'average_view_rate', 'average_view_time',
            'completion_rate', 'view_length', 'error101', 'error102', 'error200', 'error201', 'error202', 'error203',
            'error300', 'error301', 'error302', 'error303', 'error400', 'error401', 'error402', 'error403', 'error505',
            'error500', 'error501', 'error502', 'error503', 'error600', 'error601', 'error602', 'error603', 'error604',
            'error900', 'error901',
        ];
    }
}
