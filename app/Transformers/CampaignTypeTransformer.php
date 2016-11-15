<?php

namespace App\Transformers;

use App\Models\CampaignType;

class CampaignTypeTransformer extends Transformer
{
    public function transform(CampaignType $type)
    {
        return [
            'title'     => $type->title,
            'alias'     => $type->alias,
            'available' => (boolean) $type->available,
            'single'    => (boolean) $type->single,
            'has_name'  => (boolean) $type->has_name,
        ];
    }
}
