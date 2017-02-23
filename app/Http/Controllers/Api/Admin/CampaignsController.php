<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\User;
use App\Stats\StatsTransformer;
use App\Transformers\CampaignTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CampaignsController extends ApiController
{
    public function stats(Request $request)
    {
        $userId = $request->get('user_id');
        $user = User::with('campaigns')->findOrFail($userId);

        $campaigns = $user->campaigns;

        $stats = CampaignEvent::with('tag', 'campaign')
            ->select('name', 'campaign_id', 'tag_id', DB::raw('SUM(count) as count'))
            ->whereIn('campaign_id', $campaigns->pluck('id'))
            ->timeRange('today', $this->user->timezone)
            ->groupBy('name', 'campaign_id', 'tag_id')
            ->get()
            ->groupBy('campaign_id');

        $statsTransformer = new StatsTransformer;

        foreach ($campaigns as $campaign) {
            $campaign->stats = $statsTransformer->transformSumAll($stats->get($campaign->id) ?? new Collection());
        }

        return $this->collectionResponse($campaigns, new CampaignTransformer);
    }
}
