<?php

namespace App\Stats;

use Illuminate\Support\Collection;

class AnalyticsTransformer
{
    public function transformSumAll(Collection $stats)
    {
        return [
            'mobilePageviews'  => $stats->sum(function ($stat) {
                return $stat->where('name', 'mobilePageviews')->sum('count');
            }),
            'desktopPageviews' => $stats->sum(function ($stat) {
                return $stat->where('name', 'desktopPageviews')->sum('count');
            }),
        ];
    }
}
