<?php

namespace VideoAd\Http\Controllers\Api;

use Api;
use Illuminate\Http\Request;
use VideoAd\Http\Requests\CampaignTypeRequest;
use VideoAd\Models\CampaignType;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Http\Mappers\CampaignTypesMapper;

/**
 * @author Coded Design
 * Class CampaignTypesController
 * @package VideoAd\Http\Controllers\Api
 */
class CampaignTypesController extends Controller
{
    /**
     * Return the list of campaign types.
     *
     * @param CampaignTypesMapper $campaignTypesMapper
     * @return json
     */
    public function index(CampaignTypesMapper $campaignTypesMapper)
    {
        // here we are able to use CampaignType::all() because we will
        // always have a small number of types. So, there's not risk.
        return Api::respond($campaignTypesMapper, CampaignType::all());
    }

    /**
     * Add a new campaign type.
     *
     * @param CampaignTypeRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CampaignTypeRequest $request)
    {
        $type = CampaignType::create($request->all());

        return response([
            'message' => 'Successfully added a campaign type.',
            'type' => $type
        ], 201);
    }

    /**
     * Update an existing campaign type.
     *
     * @param CampaignTypeRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(CampaignTypeRequest $request, $id)
    {
        $type = CampaignType::findOrFail($id);

        $type->update($request->all());

        return response([
            'message' => 'Successfully updated the campaign type.',
            'type' => $type
        ], 200);
    }

    /**
     * Delete a campaign type.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $type = CampaignType::findOrFail($id);

        $type->delete();

        return response([
            'message' => 'Successfully deleted the campaign type.'
        ], 200) ;
    }
}
