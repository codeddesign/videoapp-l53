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
    public function index(): View
    {
        $webpack    = config('view.webpack');
        $socketIoIp = config('app.socket_io_ip');
        $apiDomain  = config('app.url');

        return view('pages.app', compact('webpack', 'socketIoIp', 'apiDomain'));
    }
}
