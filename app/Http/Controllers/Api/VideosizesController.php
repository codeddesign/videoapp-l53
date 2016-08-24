<?php

namespace VideoAd\Http\Controllers\Api;

use VideoAd\Http\Controllers\Controller;

/**
 * @author Coded Design
 * Class VideosizesController
 * @package VideoAd\Http\Controllers\Api
 */
class VideosizesController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        return config('campaigns.sizes');
    }
}
