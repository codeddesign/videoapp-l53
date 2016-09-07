<?php

namespace VideoAd\Http\Controllers\Client;

use VideoAd\Models\CampaignEvent;
use VideoAd\Http\Controllers\Controller;
use VideoAd\CampaignEvents\Facades\CampaignEvent as CampaignEventFacade;

/**
 * @author Coded Design
 * Class CampaignsController
 * @package VideoAd\Http\Controllers\Client
 */
class CampaignsController extends Controller
{
    /**
     * Create a new instance of this class.
     */
    public function __construct()
    {
        $this->middleware('cors', ['only' => ['campaign']]);
    }

    /**
     * Return the campaign info, used by the player.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function campaign($id)
    {
        CampaignEvent::create(['campaign_id' => $id, 'name' => 'app', 'event' => 'load']);

        return response(CampaignEventFacade::campaignInfo($id), 200);
    }
}
