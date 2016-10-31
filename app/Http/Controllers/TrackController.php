<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\TrackRequest;
use App\Services\CampaignEvents;

class TrackController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors', ['only' => ['index']]);
    }

    /**
     * @param TrackRequest $request
     *
     * @return mixed
     */
    public function index(TrackRequest $request)
    {
        $data = $request->transform();

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
