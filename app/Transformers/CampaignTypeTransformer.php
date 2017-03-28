<?php

namespace App\Transformers;

use App\Models\CampaignType;

class CampaignTypeTransformer extends Transformer
{
    public function transform(CampaignType $type)
    {
        return [
            'id'        => (int) $type->id,
            'title'     => $type->title,
            'available' => (boolean) $type->available,
            'has_name'  => (boolean) $type->has_name,
        ];
    }
}
