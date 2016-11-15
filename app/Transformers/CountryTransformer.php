<?php

namespace App\Transformers;

use Phine\Country\Country;

class CountryTransformer extends Transformer
{
    public function transform(Country $country)
    {
        return [
            'name' => $country->getShortName(),
            'code' => $country->getAlpha2Code(),
        ];
    }
}
