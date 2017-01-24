<?php

namespace App\Transformers;

use App\Models\Website;

class WebsiteTransformer extends Transformer
{
    public function transform(Website $site)
    {
        $transformedSite = [
            'id'         => (int) $site->id,
            'domain'     => $site->domain,
            'link'       => $site->link,
            'approved'   => (boolean) $site->approved,
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
}
