<?php

namespace App\CampaignEvents;

use App\CampaignEvents\Repositories\CampaignInterface;
use App\CampaignEvents\Transformers\CampaignTransformer;

class Manager implements CampaignEventInterface
{
    /**
     * The Campaign Repository instance.
     *
     * @var Campaign
     */
    protected $campaign;

    /**
     * Manager constructor.
     *
     * @param \App\CampaignEvents\Repositories\CampaignInterface $campaign
     */
    public function __construct(CampaignInterface $campaign)
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
        $campaignDetails = $this->campaignDetails($id);

        return ($campaignDetails) ? $campaignDetails : response(['message' => 'Campaign does not exist.'], 404);
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
    protected function campaignDetails($id)
    {
        switch ($id) {
            case 0:
                // if campaign is saved in session. fetch it.
                $campaign = session(config('videoad.TEMPORARY_PREVIEW_KEY'));
                break;
            default:
                // else, fetch it form database. (with trashed included)
                $campaign = $this->campaign->findByWithTrashed('id', $id, ['videos']);
                break;
        }

        return (! $campaign) ? false : CampaignTransformer::transform($campaign);
    }
}
