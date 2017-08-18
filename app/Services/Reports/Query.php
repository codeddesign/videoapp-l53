<?php

namespace App\Services\Reports;

use App\Models\CampaignEvent;
use App\Models\Report;
use App\Sessions\DatabaseSessions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Query
{
    /**
     * @var \App\Models\Report
     */
    protected $report;

    /**
     * Query constructor.
     *
     * @param \App\Models\Report $report
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function campaignEvents()
    {
        $stats = CampaignEvent::query()
            ->with('tag', 'website', 'campaign', 'campaign.type', 'backfill')
            ->select('name', 'tag_id', 'backfill_id', 'campaign_id', 'website_id', 'status', DB::raw('SUM(count) as count'))
            ->groupBy('name', 'tag_id', 'backfill_id', 'campaign_id', 'website_id', 'status')
            ->where('name', '!=', 'campaignRequests')
            ->userStats($this->report->user);

        $stats = $this->filter($stats);

        $stats = $stats->get();

        $eagerLoads = ['website', 'campaign', 'campaign.type', 'campaign.type.adType'];

        $sessions = (new DatabaseSessions)
            ->fetch($this->report->dateRange(), $this->report->user->timezone, $eagerLoads)
            ->map(function ($item) {
                $item->name = 'sessions';
                $item->tag  = new \stdClass();
                $item->tag->platform_type = $item->platform_type;

                return $item;
            });

        return collect(array_merge($stats->all(), $sessions->all()));
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function filter($query)
    {
        $query = $query->timeRange($this->report->dateRange(), $this->report->user->timezone);

        if ($this->report->filter['value'] === '') {
            return $query;
        }

        $type     = $this->filterType();
        $operator = $this->filterOperator();
        $value    = $this->filterValue();

        if ($type === null) {
            return $query;
        }

        return $query->whereHas($type['relation'], function ($query) use ($type, $operator, $value) {
            $query->where($type['property'], $operator, $value);
        });
    }

    public function mapTypeToModel($type)
    {
        $availableFilters = [
            'tag' => [
                'advertiser'    => 'advertiser',
                'description'   => 'description',
                'platform_type' => 'platform_type',
                'tag_type'      => 'type',
            ],

            'campaign.type.adType' => [
                'ad_type' => 'name',
            ],

            'website' => [
                'website' => 'domain',
            ],

            'campaign.user' => [
                'user_company' => 'company',
            ],
        ];

        foreach ($availableFilters as $relation => $filters) {
            if (in_array($type, array_keys($filters))) {
                return [
                    'relation' => $relation,
                    'property' => $filters[$type],
                ];
            }
        }

        return;
    }

    protected function filterType()
    {
        $type = $this->report->filter['type'];

        return $this->mapTypeToModel($type);
    }

    protected function filterOperator()
    {
        $filter = $this->report->filter['filter'];

        $operators = [
            'doesNotContain' => 'not ilike',
            'contains'       => 'ilike',
            'is'             => '=',
            'isNot'          => '!=',
        ];

        return $operators[$filter];
    }

    protected function filterValue()
    {
        $filter = $this->report->filter['filter'];
        $value  = $this->report->filter['value'];

        if ($filter === 'doesNotcontain' || $filter === 'contains') {
            $value = "%{$value}%";
        }

        return $value;
    }
}
