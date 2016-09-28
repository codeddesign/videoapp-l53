<?php

namespace App\Http\Mappers;

use App\Models\CampaignType;

/**
 * @author Coded Design
 *
 * @package Http\Mappers
 */
class CampaignTypesMapper
{
    /**
     * @param CampaignType $type
     * @return array
     */
    public function map(CampaignType $type)
    {
        return [
            'title' => $type->title,
            'alias' => $type->alias,
            'available' => (boolean) $type->available,
            'single' => (boolean) $type->single,
            'has_name' => (boolean) $type->has_name,
        ];
    }
}
