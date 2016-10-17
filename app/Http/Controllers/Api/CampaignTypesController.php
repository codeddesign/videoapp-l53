<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CampaignTypeRequest;
use App\Models\CampaignType;
use App\Transformers\CampaignTypeTransformer;

class CampaignTypesController extends ApiController
{
    /**
     * Return the list of campaign types.
     */
    public function index()
    {
        $campaignTypes = CampaignType::all();

        return $this->collectionResponse($campaignTypes, new CampaignTypeTransformer);
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

        return $this->jsonResponse([
            'message' => 'Successfully added a campaign type.',
            'type' => $type,
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

        return $this->jsonResponse([
            'message' => 'Successfully updated the campaign type.',
            'type' => $type,
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

        return $this->jsonResponse([
            'message' => 'Successfully deleted the campaign type.',
        ], 200);
    }
}
