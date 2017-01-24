<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\User;

class PluginController extends Controller
{
    /**
     * PluginController constructor.
     */
    public function __construct()
    {
        $this->middleware('cors', ['only' => ['getCampaignAdd']]);
    }

    /**
     * It creates a new campaign.
     *
     * @param Request $request
     *
     * @return array
     */
    public function campaignAdd(Request $request)
    {
        $site = Website::byLink(refererUtil());
        if (! $site) {
            return response(['error' => 'This site is not approved. Contact your admin.']);
        }

        $user = User::find($site->user_id);

        $campaign = $user->addCampaign([
            'type' => 'standard',
            'name' => '',
            'video' => $request->get('video_url'),
            'size' => 'auto',
        ]);

        return response([
            'campaign' => $campaign->id,
        ]);
    }
}
