<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\User;
use App\Models\WordpressSite;
use App\Stats\StatsTransformer;
use App\Transformers\WordpressSiteTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WebsitesController extends ApiController
{
    public function pending()
    {
        $pendingWebsites = WordpressSite::where('approved', false)->get();

        return $this->collectionResponse($pendingWebsites, new WordpressSiteTransformer);
    }

    public function stats(Request $request)
    {
        $userId = $request->get('user_id');
        $user = User::with('wordpressSites')->findOrFail($userId);

        $sites = $user->wordpressSites;

        $stats = CampaignEvent::with('website', 'tag')
            ->whereIn('website_id', $sites->pluck('id'))
            ->timeRange('today')
            ->get()
            ->groupBy('tag_id');

        $statsTransformer = new StatsTransformer;

        foreach ($sites as $site) {
            $site->stats = $statsTransformer->transformSumAll($stats->get($site->id) ?? new Collection());
        }

        return $this->collectionResponse($sites, new WordpressSiteTransformer);
    }
}
