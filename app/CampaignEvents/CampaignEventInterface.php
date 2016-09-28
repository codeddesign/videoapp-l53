<?php

namespace App\CampaignEvents;

/**
 * @author Coded Design
 * Interface CampaignEventInterface
 * @package App\CampaignEvents
 */
interface CampaignEventInterface
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
