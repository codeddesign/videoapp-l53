<?php

namespace App\Transformers;

use App\Models\CampaignType;
use League\Fractal\TransformerAbstract;

class CampaignTypeTransformer extends TransformerAbstract
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
