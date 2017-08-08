<?php

namespace App\Sessions\Parsers;

use Illuminate\Database\Eloquent\Collection;

class ImpressionSessionParser implements SessionParser
{
    public function parse($params, $value)
    {
        return [
            'type'        => 'impression',
            'website_id'  => $params[1],
            'campaign_id' => $params[2],
            'tag_id'      => $params[3],
            'backfill_id' => null,
            'session_id'  => $params[4],
            'count'       => $value,
            'platform'    => null,
            'ecpm'        => 0,
        ];
    }

    public function addInfo($session, Collection $collection)
    {
        $tag = $collection->find($session['tag_id']);

        if ($tag === null) {
            return;
        }

        return array_merge($session, [
            'platform' => $tag->platform_type,
            'ecpm'     => $tag->ecpm,
        ]);
    }
}
