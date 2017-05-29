<?php

namespace App\Http\Controllers;

use App\Geolite\Location;
use App\Models\Backfill;
use App\Models\Campaign;
use App\Models\Tag;
use App\Models\Website;
use Carbon\Carbon;
use Datadogstatsd;
use Illuminate\Redis\RedisManager;

class CampaignsController extends Controller
{
    /**
     * CampaignsController constructor.
     */
    public function __construct()
    {
        $this->middleware('cors', ['only' => ['campaign']]);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function campaign($id = 0)
    {
        // return the campaign information used by the player.
        $campaign = Campaign::forPlayer($id);

        if (! $campaign) {
            return response(['message' => 'Campaign does not exist.'], 404);
        }

        $request = request();

        $referer = $request->get('referrer') ?? $request->server('HTTP_REFERER');
        $ip      = $request->get('ip') ?? ipUtil();

        $website = Website::websiteByLink($referer);

        $location = Location::byIp($ip);

        if (! request()->get('xml') && ! $website && ! request()->get('test')) {
            return response('Unauthorized.', 401);
        }

        if ($request->get('test')) {
            $tags = [Tag::findOrFail($request->get('test'))];
        } else {
            $tags = Tag::forRequest($location, $website);
        }

        if (request()->get('xml') && $referer !== null) {
            $redis  = app(RedisManager::class);
            $domain = Website::linkDomain($referer);
            $date   = Carbon::now()->format('mdY');
            $redis->hsetnx('sm_domains', $domain, $date);
        }

        $platform = $request->get('platform');
        $backfill = Backfill::forRequest($website, $campaign['ad_type'], $platform);

        Datadogstatsd::increment('video-app.campaign_request', 1);

        return $this->response([
            'campaign'   => $campaign,
            'tags'       => $tags,
            'backfill'   => $backfill,
            'ip'         => $ip,
            'location'   => $location,
            'website_id' => $website->id ?? null,
        ], 200);
    }

    private function response($data)
    {
        if (! request()->get('xml')) {
            return response($data);
        }

        $data['platform'] = 'desktop';
        $agent            = request()->server('HTTP_USER_AGENT');

        $options = 'Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini';
        foreach (explode('|', $options) as $option) {
            if (stripos($agent, $option) !== false) {
                $data['platform'] = 'mobile';
            }
        }

        return response()
            ->view('campaign.tag', $data)
            ->header('Content-Type', 'text/xml');
    }
}
