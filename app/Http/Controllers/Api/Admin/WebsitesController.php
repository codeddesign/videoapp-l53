<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\WordpressSite;
use App\Transformers\WordpressSiteTransformer;

class WebsitesController extends ApiController
{
    public function pending()
    {
        $pendingWebsites = WordpressSite::where('approved', false)->get();

        return $this->collectionResponse($pendingWebsites, new WordpressSiteTransformer);
    }
}
