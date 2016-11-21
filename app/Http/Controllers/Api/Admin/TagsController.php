<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreTagRequest;
use App\Models\Tag;
use App\Transformers\TagTransformer;
use Illuminate\Http\Request;

class TagsController extends ApiController
{
    public function index()
    {
        $tags = Tag::all()->sortBy('id');

        return $this->collectionResponse($tags, new TagTransformer);
    }

    public function store(StoreTagRequest $request)
    {
        Tag::create($request->transform());

        $tags = Tag::all();

        return $this->collectionResponse($tags, new TagTransformer);
    }

    public function update($id, StoreTagRequest $request)
    {
        $tag = Tag::findOrFail($id);

        $tag->update($request->transform());

        $tags = Tag::all()->sortBy('id');

        return $this->collectionResponse($tags, new TagTransformer);
    }

    public function activate($id, Request $request)
    {
        $tag = Tag::findOrFail($id);

        $tag->active = $request->get('status');
        $tag->save();

        return $this->itemResponse($tag, new TagTransformer);
    }
}
