<?php

namespace App\Http\Controllers;

use App\Geolite\Location;
use App\Models\Campaign;
use App\Models\Tag;
use App\Models\Website;
use Datadogstatsd;

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

        $websiteId = Website::idByLink($referer);

        $location = Location::byIp($ip);

        if ($request->get('test')) {
            $tags = [Tag::findOrFail($request->get('test'))];
        } else {
            $tags = Tag::forRequest($location, $websiteId);
        }

        Datadogstatsd::increment('video-app.campaign_request', 1);

        return response(array_merge($campaign, [
            'tags'       => $tags,
            'ip'         => $ip,
            'location'   => $location,
            'website_id' => $websiteId,
        ]), 200);
    }
}
