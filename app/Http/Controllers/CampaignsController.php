<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignEvent;

/**
 * @author Coded Design
 */
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

        if(isset($campaign['campaign']['id'])) {
            CampaignEvent::create(['campaign_id' => $id, 'name' => 'app', 'event' => 'load']);
        }

        return response(array_merge($campaign, [
            'tags' => env_adTags(),
            'ip' => ipUtil(),
        ]), 200);
    }
}