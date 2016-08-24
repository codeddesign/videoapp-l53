<?php

namespace VideoAd\Http\Controllers\Api;

use Api;
use Illuminate\Support\Facades\Session;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Http\Mappers\CampaignMapper;
use VideoAd\Http\Requests\CampaignRequest;

/**
 * @author Adib Hanna <adibhanna@gmail.com>
 * Class CampaignsController
 * @package VideoAd\Http\Controllers\Api
 */
class CampaignsController extends Controller
{
    /**
     * Key for session that holds the temporary preview data.
     */
    const TEMPORARY_PREVIEW_KEY = 'temporary_campaign_preview_key';

    /**
     * List a paginated list of the campaigns.
     *
     * @param CampaignMapper $campaignMapper
     * @return json
     */
    public function index(CampaignMapper $campaignMapper)
    {
        $campaigns = auth()->user()->campaigns()->paginate();

        return Api::respond($campaignMapper, $campaigns);
    }

    /**
     * Show a specific campaign.
     *
     * @param CampaignMapper $campaignMapper
     * @param $id
     * @return json
     */
    public function show(CampaignMapper $campaignMapper, $id)
    {
        return Api::respond($campaignMapper,  auth()->user()->campaigns()->findOrFail($id));
    }

    /**
     * Preview the campaign link.
     *
     * @param CampaignRequest $request
     * @return array
     */
    public function storePreviewLink(CampaignRequest $request)
    {
        // pass the following: name, size, type, video
        $campaign = auth()->user()->addCampaign($request->all(), $toSession = true);

        Session::set(self::TEMPORARY_PREVIEW_KEY, $campaign);

        return [
            'url' => $this->getEmbedLink()
        ];
    }

    /**
     * Store a new campaign.
     *
     * @param CampaignRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CampaignRequest $request)
    {
        // pass the following when POSTing: name, size, type, video

        Session::remove(self::TEMPORARY_PREVIEW_KEY);

        $campaign = auth()->user()->addCampaign($request->all());

        return response([
            'message' => 'Successfully added a campaign.',
            'campaign' => $campaign,
            'url' => $this->getEmbedLink($campaign->id),
        ], 201);
    }

    /**
     * Update a campaign.
     * @todo
     *
     * @param CampaignRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(CampaignRequest $request, $id)
    {
        $campaign = auth()->user()->campaigns()->findOrFail($id);

        $campaign_type_id = $request->campaign_type_id;

        $data = $request->all() + ['campaign_type_id' => $campaign_type_id];

        $campaign->update($data);

        return response([
            'message' => 'Successfully updated a campaign.',
            'campaign' => $campaign
        ], 200);
    }

    /**
     * Delete a campaign.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $campaign = auth()->user()->campaigns()->findOrFail($id);

        $campaign->delete();

        return response([
            'message' => 'Successfully deleted the campaign.'
        ], 200);
    }

    /**
     * Generate the embed link.
     * example: http://domain.com/p{number}.js
     * where 'number' is an interger.
     *
     * @param int $campaignId
     * @return string
     */
    public function getEmbedLink($campaignId = 0)
    {
        $pattern = '%s/p%s.js';

        return sprintf($pattern, env('PLAYER_HOST'), $campaignId);
    }
}
