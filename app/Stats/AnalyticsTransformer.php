<?php

namespace App\Stats;

use Illuminate\Support\Collection;

class AnalyticsTransformer
{
    public function transformSumAll(Collection $stats)
    {
        return [
            'mobilePageviews'  => $stats->sum('mobile-pageviews'),
            'desktopPageviews' => $stats->sum('desktop-pageviews'),
        ];
    }
}
