<?php

namespace App\Transformers;

use App\Models\AdType;

class AdTypeTransformer extends Transformer
{
    public function transform(AdType $type)
    {
        return [
            'id'   => $type->id,
            'name' => $type->name,
        ];
    }
}
