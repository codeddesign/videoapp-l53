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
     * Return the campaign info, used by the player.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function __invoke($id)
    {
        CampaignEvent::create(['campaign_id' => $id, 'name' => 'app', 'event' => 'load']);

        return response(CampaignEventFacade::campaignInfo($id), 200);
    }
}
