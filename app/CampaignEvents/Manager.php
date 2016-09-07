<?php

namespace VideoAd\CampaignEvents;

use VideoAd\Models\Campaign;

/**
 * @author Coded Design
 * Class Manager
 * @package VideoAd\CampaignEvents
 */
class Manager implements CampaignEvent
{
    /**
     * The Campaign Model instance.
     *
     * @var Campaign
     */
    protected $campaign;

    /**
     * Manager constructor.
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Return the campaign info.
     *
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function campaignInfo($id)
    {
        return (!$campaign = $this->forPlayer($id)) ?
            response(['message' => 'Campaign does not exist.'], 404) :
            $campaign;
    }

    /**
     * Returns campaign details and information about campaign's type.
     * First it makes and attempt to fetch campaign data from session,
     * in case it's some data in preview. Otherwise, if non-zero id
     * is provided it gets it from database.
     *
     * @param int $id
     *
     * @return array
     */
    protected function forPlayer($id)
    {
        switch ($id) {
            case 0:
                // if campaign is saved in session. fetch it.
                $campaign = session(config('videoad.TEMPORARY_PREVIEW_KEY'));
                break;
            default:
                // else, fetch it form database.
                $campaign = $this->campaign->withTrashed()->with('videos')->findOrFail($id);
                break;
        }

        return (!$campaign) ? false : CampaignInfoTransformer::transform($campaign);
    }
}
