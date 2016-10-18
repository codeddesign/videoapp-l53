<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * Show the app page.
     *
     * @return \Illuminate\View\View
     */
    public function index() : View
    {
    	$webpack = config('view.webpack');
        return view('pages.app', ['webpack' => $webpack]);
    }
}
