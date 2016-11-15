<?php

namespace App\Transformers;

use Phine\Country\Subdivision;

class SubdivisionTransformer extends Transformer
{
    public function transform(Subdivision $subdivision)
    {
        return [
            'name' => $subdivision->getName(),
            'code' => $subdivision->getCode(),
        ];
    }
}
