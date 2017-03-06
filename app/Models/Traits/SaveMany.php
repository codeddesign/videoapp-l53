<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait SaveMany
{
    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @param Carbon                         $timestamp
     *
     * @return mixed
     */
    public static function saveMany($collection, $timestamp = null)
    {
        $table = with(new static)->getTable();

        if (! $timestamp) {
            $timestamp = Carbon::now();
        }

        /** @var Builder $db */
        $db = app('db')->table($table);

        $itemsArray = $collection->map(function ($item) use ($timestamp) {
            return array_merge(
                $item,
                [
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]
            );
        });

        $db->insert($itemsArray->toArray());

        return $itemsArray;
    }
}
