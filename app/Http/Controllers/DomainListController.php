<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Redis\RedisManager;

class DomainListController extends Controller
{
    public function domains(Request $request)
    {
        $redis  = app(RedisManager::class);

        $filter = $request->get('date') ? $request->get('date') : Carbon::now()->format('mdY');

        $domains = collect($redis->hgetall('sm_domains'));

        $domains = $domains->filter(function ($date) use ($filter) {
            return $date === $filter;
        });

        foreach ($domains as $domain => $date) {
            echo $domain;
            echo '<br>';
        }
    }
}
