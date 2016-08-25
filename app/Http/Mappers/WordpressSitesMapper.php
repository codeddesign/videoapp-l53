<?php

namespace VideoAd\Http\Mappers;

use VideoAd\Models\Wordpress;

/**
 * @author Coded Design
 * Class WordpressSitesMapper
 * @package Http\Mappers
 */
class WordpressSitesMapper
{
    /**
     * @param Wordpress $site
     * @return array
     */
    public function map(Wordpress $site)
    {
        return [
            'id' => (int) $site->id,
            'domain' => $site->domain,
            'link' => $site->link,
            'create_at' => $site->create_at,
            'updated_at' => $site->updated_at,
            'user_id' => (int) $site->user->id
        ];
    }
}