<?php

namespace App\Transformers;

use App\Models\WordpressSite;

class WordpressSiteTransformer extends Transformer
{
    public function transform(WordpressSite $site)
    {
        return [
            'id'         => (int) $site->id,
            'domain'     => $site->domain,
            'link'       => $site->link,
            'approved'   => (boolean) $site->approved,
            'created_at' => $site->created_at->timestamp,
            'user_id'    => (int) $site->user->id,
        ];
    }
}
