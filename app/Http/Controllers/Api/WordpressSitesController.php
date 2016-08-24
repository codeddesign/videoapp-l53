<?php

namespace VideoAd\Http\Controllers\Api;

use Api;
use VideoAd\Http\Controllers\Controller;
use VideoAd\Http\Mappers\WordpressSitesMapper;

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

    public function store()
    {

    }

    public function destroy($id)
    {
        //
    }
}
