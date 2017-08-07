<?php

namespace App\Sessions\Parsers;

use Illuminate\Database\Eloquent\Collection;

class VisitSessionParser implements SessionParser
{
    public function parse($params, $value)
    {
        return [
            'type'        => 'visit',
            'website_id'  => $params[1],
            'campaign_id' => $params[2],
            'tag_id'      => null,
            'backfill_id' => null,
            'session_id'  => $params[4],
            'count'       => $value,
            'platform'    => $params[3],
            'ecpm'        => 0,
        ];
    }

    public function addInfo($session, Collection $collection)
    {
        return $session;
    }
}
