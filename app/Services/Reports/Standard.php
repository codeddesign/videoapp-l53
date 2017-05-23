<?php

namespace App\Services\Reports;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Collection;

class Standard
{
    public static function create(User $user)
    {
        $reports = new Collection([
            [
                'title'      => 'Today',
                'date_range' => 'today',
            ],
            [
                'title'      => 'Yesterday',
                'date_range' => 'yesterday',
            ],
            [
                'title'      => 'Last 3 Days',
                'date_range' => 'threeDays',
            ],
            [
                'title'      => 'Last 7 Days',
                'date_range' => 'sevenDays',
            ],
            [
                'title'      => 'Last 30 Days',
                'date_range' => 'thirtyDays',
            ],
            [
                'title'      => 'This Month',
                'date_range' => 'thisMonth',
            ],
            [
                'title'      => 'Last Month',
                'date_range' => 'lastMonth',
            ],
        ]);

        $reports = $reports->map(function ($report) use ($user) {
            return array_merge($report, [
                'user_id'          => $user->id,
                'recipient'        => $user->email,
                'sort_by'          => 'website',
                'sort_by'          => 'website',
                'schedule'         => 'once',
                'deletable'        => false,
                'included_metrics' => json_encode(Report::allUserMetrics()),
                'filter'           => json_encode([
                    'type'   => '',
                    'value'  => '',
                    'filter' => '',
                ]),
            ]);
        });

        Report::saveMany($reports);
    }
}
