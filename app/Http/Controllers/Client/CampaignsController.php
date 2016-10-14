<?php

namespace App\Http\Controllers\Client;

use App\Models\CampaignEvent;
use App\Http\Controllers\Controller;
use App\CampaignEvents\Facades\CampaignEvent as CampaignEventFacade;

/**
 * @author Coded Design
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
