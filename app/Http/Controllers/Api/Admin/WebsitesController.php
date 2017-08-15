<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\WebsiteApproved;
use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\User;
use App\Models\Website;
use App\Stats\StatsTransformer;
use App\Transformers\WebsiteTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\DB;

class WebsitesController extends ApiController
{
    public function index()
    {
        $websites = Website::all();

        return $this->collectionResponse($websites, new WebsiteTransformer);
    }

    public function store(Request $request)
    {
        $user = User::find($request->get('user_id'));

        $website = $user->websites()->create([
            'domain' => $request->get('domain'),
        ]);

        $website->approved = true;
        $website->waiting  = false;
        $website->save();

        return $this->collectionResponse($user->websites, new WebsiteTransformer);
    }

    public function pending()
    {
        $pendingWebsites = Website::where('waiting', true)->get();

        return $this->collectionResponse($pendingWebsites, new WebsiteTransformer);
    }

    public function activate($id, Request $request)
    {
        $website = Website::findOrFail($id);

        if ($website->waiting && $request->get('status') === true) {
            event(new WebsiteApproved($website));
        }

        $website->approved = $request->get('status');
        $website->waiting  = false;
        $website->save();

        $this->clearCache();

        return $this->itemResponse($website, new WebsiteTransformer);
    }

    public function owned($id, Request $request)
    {
        $website = Website::findOrFail($id);

        $website->owned = $request->get('status');
        $website->save();

        $this->clearCache();

        return $this->itemResponse($website, new WebsiteTransformer);
    }

    public function stats(Request $request)
    {
        $userId = $request->get('user_id');
        $user   = User::with('websites')->findOrFail($userId);
        $range  = $request->get('time');

        $sites = $user->websites;

        $stats = CampaignEvent::with('website', 'tag')
            ->select('name', 'website_id', 'tag_id', DB::raw('SUM(count) as count'))
            ->whereIn('website_id', $sites->pluck('id'))
            ->timeRange($range, $this->user->timezone)
            ->groupBy('name', 'website_id', 'tag_id')
            ->get()
            ->groupBy('website_id');

        $statsTransformer = new StatsTransformer;

        foreach ($sites as $site) {
            $site->stats = $statsTransformer->transformSumAll($stats->get($site->id) ?? new Collection());
        }

        return $this->collectionResponse($sites, new WebsiteTransformer);
    }

    protected function clearCache()
    {
        $cache = app(Repository::class);

        $cache->tags(['tags', 'website'])->flush();
    }
}
