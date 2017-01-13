<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreTagRequest;
use App\Models\CampaignEvent;
use App\Models\DateRange;
use App\Models\Tag;
use App\Stats\StatsTransformer;
use App\Transformers\TagTransformer;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagsController extends ApiController
{
    public function index(Request $request)
    {
        $tags = Tag::all()->sortByDesc('priority_count');

        $compareRange = $request->get('compareRange');

        $stats = CampaignEvent::query()->with('tag')->where('tag_id', '!=', null);

        $days = 1;

        if ($compareRange) {
            $stats->timeRange($compareRange);
            $dateRange = DateRange::byName($compareRange);
            $days      = $dateRange->days();
        }

        // When using "today" as the range, the
        // days count is 0 when it should be 1
        if ($days === 0) {
            $days = 1;
        }

        $stats = $stats->get()->groupBy('tag_id');

        $statsTransformer = new StatsTransformer;

        foreach ($tags as $tag) {
            $tag->stats = $statsTransformer->sumAllAndAverage($stats->get($tag->id) ?? new Collection(), $days);
        }

        return $this->collectionResponse($tags, new TagTransformer);
    }

    public function store(StoreTagRequest $request)
    {
        Tag::create($request->transform());

        $tags = Tag::all();

        $this->clearTagsCache();

        return $this->collectionResponse($tags, new TagTransformer);
    }

    public function update($id, StoreTagRequest $request)
    {
        $tag = Tag::findOrFail($id);

        $tag->update($request->transform());

        $tags = Tag::all()->sortBy('id');

        $this->clearTagsCache();

        return $this->collectionResponse($tags, new TagTransformer);
    }

    public function activate($id, Request $request)
    {
        $tag = Tag::findOrFail($id);

        $tag->active = $request->get('status');
        $tag->save();

        $this->clearTagsCache();

        return $this->itemResponse($tag, new TagTransformer);
    }

    protected function clearTagsCache()
    {
        $cache = app(Repository::class);

        $cache->tags(['tags'])->flush();
    }
}
