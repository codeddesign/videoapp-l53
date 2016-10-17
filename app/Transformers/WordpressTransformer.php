<?php

namespace App\Transformers;

use App\Models\WordpressSite;
use League\Fractal\TransformerAbstract;

class WordpressTransformer extends TransformerAbstract
{
    public function transform(WordpressSite $site)
    {
        return [
            'id'         => (int) $site->id,
            'domain'     => $site->domain,
            'link'       => $site->link,
            'created_at' => $site->created_at,
            'updated_at' => $site->updated_at,
            'user_id'    => (int) $site->user->id,
        ];
    }

}
