<?php

namespace VideoAd\Http\Controllers;

class PagesController extends Controller
{
    public function index()
    {
        return view('pages.app');
    }
}
