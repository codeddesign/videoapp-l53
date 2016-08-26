<?php

namespace VideoAd\Http\Controllers;

use Illuminate\Http\Request;
use VideoAd\Models\Campaign;
use VideoAd\Models\CampaignEvent;

/**
 * @author Coded Design
 * Class CampaignsController
 * @package VideoAd\Http\Controllers
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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function campaign(Request $request, $id = 0)
    {
        $campaign = Campaign::forPlayer($id);

        if (!$campaign) {
            return response(['message' => 'Campaign does not exist.'], 404);
        }

        CampaignEvent::create(['campaign_id' => $id, 'name' => 'app', 'event' => 'load']);

        return response($campaign, 200);
    }
}
