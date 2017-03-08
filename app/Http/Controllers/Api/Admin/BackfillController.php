<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreBackfillRequest;
use App\Models\Backfill;
use App\Models\Website;
use App\Transformers\BackfillTransformer;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;

class BackfillController extends ApiController
{
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
