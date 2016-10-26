<?php

namespace App\Http\Controllers;

use App\Services\CampaignEvents;
use Illuminate\Http\Request;
use App\Services\PlayerEvent;

class TrackController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors', ['only' => ['index']]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = $request->only(['campaign', 'status', 'source', 'tag']);

        (new CampaignEvents)->handle($data);

        // return one pixel
        return response($this->onePixel())->header('Content-Type', 'image/png');
    }

    /**
     * One pixel image.
     *
     * @return mixed
     */
    protected function onePixel()
    {
        return base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII='
        );
    }
}
