<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\WebsiteRequest;
use App\Transformers\WebsiteTransformer;

class WebsitesController extends ApiController
{
    /**
     * Show the list of websites belonging to a user.
     */
    public function index()
    {
        $sites = $this->user->websites;

        return $this->collectionResponse($sites, new WebsiteTransformer);
    }

    /**
     * Store a new website.
     *
     * @param WebsiteRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(WebsiteRequest $request)
    {
        $site = $this->user->websites()->create($request->all());

        return $this->jsonResponse([
            'message' => 'Successfully added a website.',
            'site' => (new WebsiteTransformer)->transform($site),
        ], 201);
    }

    /**
     * Delete a site.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $site = $this->user->websites()->findOrFail($id);

        $site->delete();

        return $this->jsonResponse([
            'message' => 'Successfully deleted a website.',
        ], 200);
    }
}
