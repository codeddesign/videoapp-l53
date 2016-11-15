<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract
{
    protected function date($date)
    {
        if ($date === null) {
            return;
        }

        return $date->toIso8601String();
    }
}
