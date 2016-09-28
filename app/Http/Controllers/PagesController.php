<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * @author Coded Design
 *
 * @package App\Http\Controllers
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
