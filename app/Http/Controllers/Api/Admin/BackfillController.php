<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreBackfillRequest;
use App\Models\Backfill;
use App\Models\CampaignEvent;
use App\Models\DateRange;
use App\Models\Website;
use App\Stats\StatsTransformer;
use App\Transformers\BackfillTransformer;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BackfillController extends ApiController
{
    public function index(Request $request)
    {
        $backfill = Backfill::all();

        $compareRange   = $request->get('compareRange');

        if ($compareRange) {
            $stats = CampaignEvent::query()
                ->with('backfill')
                ->select('name', 'backfill_id', DB::raw('created_at::date'), DB::raw('SUM(count) as count'))
                ->where('backfill_id', '!=', null)
                ->where('name', '=', 'backfill')
                ->groupBy('name', 'backfill_id', DB::raw('created_at::date'))
                ->timeRange($compareRange, $this->user->timezone);

            $stats = $stats->get()
                ->groupBy('backfill_id');

            $days = DateRange::byName($compareRange)->days() ?: 1;

            $statsTransformer = new StatsTransformer;

            foreach ($backfill as $b) {
                $b->stats = $statsTransformer->sumAllAndAverage($stats->get($b->id) ?? new Collection, $days);
                //$b->stats->put('pageviews', $pageViews);
            }
        }

        return $this->collectionResponse($backfill, new BackfillTransformer);
    }

    public function store($websiteId, StoreBackfillRequest $request)
    {
        $website = Website::findOrFail($websiteId);

        $backfill = Backfill::create(array_merge(
            ['website_id' => $website->id],
            $request->transform()
        ));

        $this->clearTagsCache();

        return $this->itemResponse($backfill, new BackfillTransformer);
    }

    public function update($id, StoreBackfillRequest $request)
    {
        $backfill = Backfill::findOrFail($id);

        $backfill->update($request->transform());

        $this->clearTagsCache();

        return $this->itemResponse($backfill, new BackfillTransformer);
    }

    public function activate($id, Request $request)
    {
        $backfill = Backfill::findOrFail($id);

        $backfill->active = $request->get('status');
        $backfill->save();

        $this->clearTagsCache();

        return $this->itemResponse($backfill, new BackfillTransformer());
    }

    public function destroy(Request $request)
    {
        $ids = $request->get('backfill');

        Backfill::query()->whereIn('id', $ids)->delete();

        $this->clearTagsCache();

        return $this->jsonResponse(['deleted_backfill' => $ids]);
    }

    protected function clearTagsCache()
    {
        $cache = app(Repository::class);

        $cache->tags(['backfill'])->flush();
    }
}
