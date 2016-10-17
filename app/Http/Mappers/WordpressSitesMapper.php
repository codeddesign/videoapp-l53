<?php

namespace App\Http\Mappers;

use App\Models\WordpressSite;

/**
 * @author Coded Design
 */
class WordpressSitesMapper
{
    /**
     * @param WordpressSite $site
     *
*@return array
     */
    public function map(WordpressSite $site)
    {
        return [
            'id' => (int) $site->id,
            'domain' => $site->domain,
            'link' => $site->link,
            'create_at' => $site->create_at,
            'updated_at' => $site->updated_at,
            'user_id' => (int) $site->user->id,
        ];
    }
}
