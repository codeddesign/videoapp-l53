<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VideosizesController extends Controller
{
    /**
     * Return the video sizes.
     * Stored in a config file.
     *
     * @return View
     */
    public function index()
    {
        return config('campaigns.sizes');
    }
}
