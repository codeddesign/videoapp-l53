<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\User;
use App\Models\Website;
use App\Stats\StatsTransformer;
use App\Transformers\WebsiteTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WebsitesController extends ApiController
{
    public function index()
    {
        $websites = Website::all();

        return $this->collectionResponse($websites, new WebsiteTransformer);
    }

    public function pending()
    {
        $pendingWebsites = Website::where('approved', false)->get();

        return $this->collectionResponse($pendingWebsites, new WebsiteTransformer);
    }

    public function activate($id, Request $request)
    {
        $website = Website::findOrFail($id);

        $website->approved = $request->get('status');
        $website->save();

        return $this->itemResponse($website, new WebsiteTransformer);
    }

    public function stats(Request $request)
    {
        $userId = $request->get('user_id');
        $user = User::with('websites')->findOrFail($userId);

        $sites = $user->websites;

        $stats = CampaignEvent::with('website', 'tag')
            ->whereIn('website_id', $sites->pluck('id'))
            ->timeRange('today', $this->user->timezone)
            ->get()
            ->groupBy('tag_id');

        $statsTransformer = new StatsTransformer;

        foreach ($sites as $site) {
            $site->stats = $statsTransformer->transformSumAll($stats->get($site->id) ?? new Collection());
        }

        return $this->collectionResponse($sites, new WebsiteTransformer);
    }
}
