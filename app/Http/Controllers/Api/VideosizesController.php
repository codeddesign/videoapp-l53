<?php

namespace VideoAd\Http\Controllers\Api;

use VideoAd\Http\Controllers\Controller;

class VideosizesController extends Controller
{
    public function index()
    {
        return config('campaigns.sizes');
    }
}
