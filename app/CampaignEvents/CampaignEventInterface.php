<?php

namespace App\CampaignEvents;

interface CampaignEventInterface
{
    /**
     * Return the campaign info.
     * (used by the player).
     *
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function campaignInfo($id);
}
