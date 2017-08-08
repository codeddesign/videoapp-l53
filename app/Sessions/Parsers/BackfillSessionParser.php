<?php

namespace App\Sessions\Parsers;

use Illuminate\Database\Eloquent\Collection;

class BackfillSessionParser implements SessionParser
{
    public function parse($params, $value)
    {
        return [
            'type'        => 'backfill',
            'website_id'  => $params[1],
            'campaign_id' => $params[2],
            'tag_id'      => null,
            'backfill_id' => $params[3],
            'session_id'  => $params[4],
            'count'       => $value,
            'platform'    => null,
            'ecpm'        => 0,
        ];
    }

    public function addInfo($session, Collection $collection)
    {
        $backfill = $collection->find($session['backfill_id']);

        if ($backfill === null) {
            return;
        }

        return array_merge($session, [
            'platform' => $backfill->platform_type,
            'ecpm'     => $backfill->ecpm,
        ]);
    }
}
