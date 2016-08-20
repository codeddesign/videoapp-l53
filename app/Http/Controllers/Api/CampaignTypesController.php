<?php

namespace VideoAd\Http\Controllers\Api;

use Api;
use Illuminate\Http\Request;
use VideoAd\Models\CampaignType;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Http\Mappers\CampaignTypesMapper;

/**
 * @author Adib Hanna <adibhanna@gmail.com>
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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        // We are not using a request object to validate
        // this request for the sake of simplicity.
        $this->validate($request, [
            'title' => 'required|max:255',
            'alias' => 'required|max:255',
            'available' => 'boolean',
            'single' => 'boolean',
            'has_name' => 'boolean',
        ]);

        $type = CampaignType::create($request->all());

        return response([
            'message' => 'Successfully added a campaign type.',
            'type' => $type
        ], 201);
    }

    /**
     * Update an existing campaign type.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'alias' => 'required|max:255',
            'available' => 'boolean',
            'single' => 'boolean',
            'has_name' => 'boolean',
        ]);

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
