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
use Illuminate\Support\Facades\DB;

class TagsController extends ApiController
{
    public function index(Request $request)
    {
        $tags = Tag::where('user_id', null)
            ->get()
            ->sortBy('priority_count');

        $compareRange = $request->get('compareRange');

        if ($compareRange) {
            $stats = CampaignEvent::query()
                ->with('tag')
                ->select('name', 'tag_id', 'status', DB::raw('created_at::date'), DB::raw('SUM(count) as count'))
                ->where('tag_id', '!=', null)
                ->where('name', '!=', 'viewership')
                ->orWhere(function ($query) {
                    $query->where('name', '=', 'viewership')
                        ->where('status', array_search('complete', CampaignEvent::$viewership));
                })
                ->groupBy('name', 'tag_id', 'status', DB::raw('created_at::date'))
                ->timeRange($compareRange, $this->user->timezone)
                ->get()
                ->groupBy('tag_id');

            $pageViews = CampaignEvent::query()
                    ->select(DB::raw('created_at::date'), DB::raw('SUM(count) as count'))
                    ->groupBy(DB::raw('created_at::date'))
                    ->whereIn('name', ['desktopPageviews', 'mobilePageviews'])
                    ->timeRange($compareRange, $this->user->timezone)
                    ->first()->count ?? 0;

            $days = DateRange::byName($compareRange)->days() ?: 1;

            $statsTransformer = new StatsTransformer;

            foreach ($tags as $tag) {
                $tag->stats = $statsTransformer->sumAllAndAverage($stats->get($tag->id) ?? new Collection(), $days, true);
                $tag->stats->put('pageviews', $pageViews);
            }
        }

        $tags = $tags->sortBy('id');

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

    public function destroy($id)
    {
        Tag::findOrFail($id)->delete();

        $tags = Tag::all()->sortBy('id');

        return $this->collectionResponse($tags, new TagTransformer);
    }

    protected function clearTagsCache()
    {
        $cache = app(Repository::class);

        $cache->tags(['tags'])->flush();
    }
}
