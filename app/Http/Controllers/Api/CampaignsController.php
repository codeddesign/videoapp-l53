<?php

namespace VideoAd\Http\Controllers\Api;

use Api;
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
     * Store a new campaign.
     *
     * @param CampaignRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CampaignRequest $request)
    {
        $user_id = $request->user()->id;

        $campaign_type_id = $request->campaign_type_id;

        $data = $request->all() + ['user_id' => $user_id, 'campaign_type_id' => $campaign_type_id];

        // example of the data sent
        /**
         *  {
         *      "name": "campaign name",
         *      "rpm": 123,
         *      "size": "auto",
         *      "campaign_type_id":2
         *  }
         */
        $campaign = auth()->user()->campaigns()->create($data);

        return response([
            'message' => 'Successfully added a campaign.',
            'campaign' => $campaign
        ], 201);
    }

    /**
     * Update a campaign.
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
}
