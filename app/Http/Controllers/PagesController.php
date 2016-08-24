<?php

namespace VideoAd\Http\Controllers;

use Illuminate\View\View;

/**
 * @author Coded Design
 * Class PagesController
 * @package VideoAd\Http\Controllers
 */
class PagesController extends Controller
{
    /**
     * Show the app page.
     *
     * @return \Illuminate\View\View
     */
    public function index() : View
    {
        return view('pages.app');
    }
}
