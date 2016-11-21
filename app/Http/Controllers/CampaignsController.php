<?php

namespace App\Http\Controllers;

use App\Geolite\Location;
use App\Models\Campaign;
use App\Models\Tag;

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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function campaign($id = 0)
    {
        // return the campaign information used by the player.
        $campaign = Campaign::forPlayer($id);

        if (! $campaign) {
            return response(['message' => 'Campaign does not exist.'], 404);
        }

        $ip = request()->get('ip') ?? ipUtil();

        $location = Location::byIp($ip);

        $tags = Tag::forLocation($location);

        return response(array_merge($campaign, [
            'tags' => $tags,
            'ip' => $ip,
            'location' => $location
        ]), 200);
    }
}
