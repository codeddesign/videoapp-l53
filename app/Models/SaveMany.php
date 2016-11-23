<?php

namespace App\Models;

use Carbon\Carbon;

trait SaveMany
{
    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return mixed
     */
    public static function saveMany($collection)
    {
        $table = with(new static)->getTable();

        /** @var Builder $db */
        $db = app('db')->table($table);

        $itemsArray = $collection->map(function ($item) {
            return array_merge(
                $item,
                [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        });

        $db->insert($itemsArray->toArray());

        return $itemsArray;
    }
}
