<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEvent;
use App\Stats\StatsTransformer;
use App\Transformers\CampaignTransformer;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CampaignRequest;

class CampaignsController extends ApiController
{
    protected $previewKey;

    public function __construct()
    {
        parent::__construct();
        $this->previewKey = config('videoad.TEMPORARY_PREVIEW_KEY');
    }

    /**
     * List a paginated list of the campaigns.
     */
    public function index()
    {
        $campaigns = $this->user->campaigns->load('type.adType');

        $range = 'thirtyDays';

        $stats = CampaignEvent::with('tag', 'campaign', 'backfill')
            ->select('name', 'campaign_id', 'tag_id', 'backfill_id', DB::raw('SUM(count) as count'))
            ->whereIn('campaign_id', $campaigns->pluck('id'))
            ->timeRange($range, $this->user->timezone)
            ->groupBy('name', 'campaign_id', 'tag_id', 'backfill_id')
            ->get()
            ->groupBy('campaign_id');

        $statsTransformer = new StatsTransformer;

        foreach ($campaigns as $campaign) {
            $campaign->stats = $statsTransformer->transformSumAll($stats->get($campaign->id) ?? new Collection());
        }

        return $this->collectionResponse($campaigns, new CampaignTransformer);
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
        $campaign = $this->user->campaigns()->findOrFail($id);

        return $this->itemResponse($campaign, new CampaignTransformer);
    }

    /**
     * Preview the campaign link.
     *
     * @param CampaignRequest                $request
     *
     * @param \Illuminate\Redis\RedisManager $redis
     *
     * @return array
     */
    public function storePreviewLink(CampaignRequest $request, RedisManager $redis)
    {
        // pass the following: name, size, type, video
        $campaign = $this->user->addCampaign($request->all(), $toSession = true);

        $previewId = str_random(16);

        $redis->connection()->setex(
            "{$this->previewKey}.{$previewId}",
            60 * 60,
            serialize($campaign)
        );

        return response()
            ->json(['embed' => $campaign->embedCode()])
            ->withCookie(cookie('preview_id', $previewId, 60));
    }

    /**
     * Store a new campaign.
     *
     * @param CampaignRequest                $request
     *
     *
     * @param \Illuminate\Redis\RedisManager $redis
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CampaignRequest $request, RedisManager $redis)
    {
        // pass the following when POSTing: name, size, type, video
        Session::remove(config('videoad.TEMPORARY_PREVIEW_KEY'));

        $redis->connection()->del(
            "{$this->user->id}.{$this->previewKey}"
        );

        $campaign = $this->user->addCampaign($request->all());

        return response([
            'message'  => 'Successfully added a campaign.',
            'campaign' => $campaign,
            'embed'      => $campaign->embedCode(),
        ], 201);
    }

    /**
     * Update a campaign.
     *
     * @param CampaignRequest $request
     * @param                 $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(CampaignRequest $request, $id)
    {
        $campaign = $this->user->campaigns()->findOrFail($id);

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
        $campaign = $this->user->campaigns()->findOrFail($id);

        $campaign->delete();

        return response([
            'message' => 'Successfully deleted the campaign.',
        ], 200);
    }
}
