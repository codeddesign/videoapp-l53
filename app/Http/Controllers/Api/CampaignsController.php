<?php

namespace App\Http\Controllers\Api;

use App\Transformers\CampaignTransformer;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CampaignRequest;
use Illuminate\Redis\Database as Redis;

class CampaignsController extends ApiController
{
    protected $previewKey;

    public function __construct()
    {
        $this->previewKey = config('videoad.TEMPORARY_PREVIEW_KEY');
    }

    /**
     * List a paginated list of the campaigns.
     */
    public function index()
    {
        $campaigns = $this->paginate($this->user()->campaigns()->getQuery());

        return $this->paginatedCollectionResponse($campaigns, new CampaignTransformer);
    }

    /**
     * Show a specific campaign.
     *
     * @param $id
     *
     * @return json
     */
    public function show($id)
    {
        $campaign = $this->user()->campaigns()->findOrFail($id);

        return $this->itemResponse($campaign, new CampaignTransformer);
    }

    /**
     * Preview the campaign link.
     *
     * @param CampaignRequest $request
     * @param Redis           $redis
     *
     * @return array
     */
    public function storePreviewLink(CampaignRequest $request, Redis $redis)
    {
        // pass the following: name, size, type, video
        $campaign = $this->user()->addCampaign($request->all(), $toSession = true);

        $redis->connection()->set(
            "{$this->user()->id}.{$this->previewKey}",
            serialize($campaign)
        );

        return [
            'url' => $this->getEmbedLink(),
        ];
    }

    /**
     * Store a new campaign.
     *
     * @param CampaignRequest            $request
     *
     * @param \Illuminate\Redis\Database $redis
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CampaignRequest $request, Redis $redis)
    {
        // pass the following when POSTing: name, size, type, video
        Session::remove(config('videoad.TEMPORARY_PREVIEW_KEY'));

        $redis->connection()->del(
            "{$this->user()->id}.{$this->previewKey}"
        );

        $campaign = $this->user()->addCampaign($request->all());

        return response([
            'message'  => 'Successfully added a campaign.',
            'campaign' => $campaign,
            'url'      => $this->getEmbedLink($campaign->id),
        ], 201);
    }

    /**
     * Update a campaign.
     *
     * @todo ask adelin abt this.
     *
     * @param CampaignRequest $request
     * @param                 $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(CampaignRequest $request, $id)
    {
        $campaign = $this->user()->campaigns()->findOrFail($id);

        $campaign_type_id = $request->campaign_type_id;

        $data = $request->all() + ['campaign_type_id' => $campaign_type_id];

        $campaign->update($data);

        return response([
            'message'  => 'Successfully updated a campaign.',
            'campaign' => $campaign,
        ], 200);
    }

    /**
     * Delete a campaign.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $campaign = $this->user()->campaigns()->findOrFail($id);

        $campaign->delete();

        return response([
            'message' => 'Successfully deleted the campaign.',
        ], 200);
    }

    /**
     * Generate the embed link.
     * example: http://domain.com/p{number}.js
     * where 'number' is an interger.
     *
     * @param int $campaignId
     *
     * @return string
     */
    public function getEmbedLink($campaignId = 0)
    {
        $pattern = '%s/p%s.js';

        return sprintf($pattern, env('PLAYER_URL'), $campaignId);
    }
}
