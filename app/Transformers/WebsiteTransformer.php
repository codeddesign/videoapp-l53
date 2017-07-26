<?php

namespace App\Transformers;

use App\Models\Website;

class WebsiteTransformer extends Transformer
{
    protected $availableIncludes = [
        'backfill',
    ];

    public function transform(Website $site)
    {
        $transformedSite = [
            'id'         => (int) $site->id,
            'domain'     => $site->domain,
            'link'       => $site->link,
            'approved'   => (boolean) $site->approved,
            'owned'      => (boolean) $site->owned,
            'waiting'    => (boolean) $site->waiting,
            'created_at' => $site->created_at->timestamp,
            'user_id'    => (int) $site->user->id,
        ];

        if ($site->stats) {
            $transformedSite = array_merge($transformedSite, [
                'stats' => $site->stats,
            ]);
        }

        return $transformedSite;
    }

    public function includeBackfill(Website $website)
    {
        $backfill = $website->backfill;

        return $this->collection($backfill, new BackfillTransformer);
    }
}
