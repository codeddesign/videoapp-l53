<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\WordpressRequest;
use App\Transformers\WordpressSiteTransformer;

class WordpressSitesController extends ApiController
{
    /**
     * Show the list of wordpress sites belonging to a user.
     */
    public function index()
    {
        $sites = $this->user->wordpressSites;

        return $this->collectionResponse($sites, new WordpressSiteTransformer);
    }

    /**
     * Store a new wordpress site.
     *
     * @param WordpressRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(WordpressRequest $request)
    {
        $site = $this->user->wordpressSites()->create($request->all());

        return $this->jsonResponse([
            'message' => 'Successfully added a website.',
            'site' => $site,
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
        $site = $this->user->wordpressSites()->findOrFail($id);

        $site->delete();

        return $this->jsonResponse([
            'message' => 'Successfully deleted a website.',
        ], 200);
    }
}
