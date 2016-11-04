<?php

namespace App\Transformers;

use App\Models\WordpressSite;
use League\Fractal\TransformerAbstract;

class WordpressSiteTransformer extends TransformerAbstract
{
    public function transform(WordpressSite $site)
    {
        return [
            'id'         => (int) $site->id,
            'domain'     => $site->domain,
            'link'       => $site->link,
            'approved'   => (bool) $site->approved,
            'created_at' => $site->created_at->timestamp,
            'user_id'    => (int) $site->user->id,
        ];
    }
}
