<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

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
