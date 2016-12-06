<?php

namespace App\Transformers;

use App\Models\GlobalOption;

class GlobalOptionTransformer extends Transformer
{
    public function transform(GlobalOption $globalOption)
    {
        return [
            'id'         => $globalOption->id,
            'option'     => $globalOption->option,
            'value'      => $globalOption->value,
            'created_at' => $this->date($globalOption->created_at),
            'updated_at' => $this->date($globalOption->updated_at),
        ];
    }
}
