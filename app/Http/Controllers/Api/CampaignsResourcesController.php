<?php

namespace VideoAd\Http\Controllers\Api;

use VideoAd\Http\Controllers\Controller;

/**
 * @author Adib Hanna <adibhanna@gmail.com>
 * Class CampaignsResourcesController
 * @package VideoAd\Http\Controllers\Api
 */
class CampaignsResourcesController extends Controller
{
    /**
     * Return the campaign types and video sizes.
     * They are stored in the config dir under a file
     * called 'campaigns.php'
     *
     * @return json
     */
    public function index()
    {
        return config('campaigns');
    }
}
