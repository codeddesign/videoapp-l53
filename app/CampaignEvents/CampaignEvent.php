<?php

namespace VideoAd\CampaignEvents;

/**
 * @author Coded Design
 * Interface CampaignEvent
 * @package VideoAd\CampaignEvents
 */
interface CampaignEvent
{
    /**
     * Return the campaign info.
     * (used by the player)
     *
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function campaignInfo($id);
}
