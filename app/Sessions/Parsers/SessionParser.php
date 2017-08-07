<?php

namespace App\Sessions\Parsers;

use Illuminate\Database\Eloquent\Collection;

interface SessionParser
{
    public function parse($params, $value);

    public function addInfo($session, Collection $collection);
}
