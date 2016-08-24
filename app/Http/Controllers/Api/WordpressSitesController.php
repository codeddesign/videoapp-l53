<?php

namespace VideoAd\Http\Controllers\Api;

use Api;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Http\Mappers\WordpressSitesMapper;
use VideoAd\Http\Requests\WordpressRequest;

/**
 * @author Coded Design
 * Class WordpressSitesController
 * @package VideoAd\Http\Controllers\Api
 */
class WordpressSitesController extends Controller
{
    /**
     * Show the list of wordpress sites belonging to a user.
     *
     * @param WordpressSitesMapper $mapper
     * @return json
     */
    public function index(WordpressSitesMapper $mapper)
    {
        $sites = auth()->user()->wordpressSites;

        return Api::respond($mapper, $sites);
    }

    /**
     * Store a new wordpress site.
     *
     * @param WordpressRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(WordpressRequest $request)
    {
        $site = auth()->user()->wordpressSites()->create($request->all());

        return response([
            'message' => 'Successfully added a website.',
            'site' => $site
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
        $site = auth()->user()->wordpressSites()->findOrFail($id);

        $site->delete();

        return response([
            'message' => 'Successfully deleted a website.',
        ], 200);
    }
}
