<?php

namespace VideoAd\Http\Controllers;

use Illuminate\Http\Request;
use VideoAd\Services\PlayerEvent;

/**
 * @author Coded Design
 * Class TrackController
 * @package VideoAd\Http\Controllers
 */
class TrackController extends Controller
{
    /**
     * TrackController constructor.
     */
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
        PlayerEvent::save($request->only('i', 'n', 'e'));

        return response($this->onePixel())
            ->header('Content-Type', 'image/png');
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
