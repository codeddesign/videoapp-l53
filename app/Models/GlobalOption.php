<?php

namespace App\Models;

/**
 * Database Columns
 *
 * @property int    $id
 * @property string $option
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class GlobalOption extends Model
{
    protected $fillable = ['option', 'value'];
}
