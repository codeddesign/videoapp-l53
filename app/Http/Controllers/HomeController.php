<?php

namespace VideoAd\Http\Controllers;

use Illuminate\View\View;

/**
 * @author Coded Design
 * Class HomeController
 * @package VideoAd\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Show the public homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index() : View
    {
        return view('home.index');
    }
}
