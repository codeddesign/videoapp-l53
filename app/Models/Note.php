<?php

namespace App\Models;

/**
 * Database Columns
 *
 * @property int    $id
 * @property string $content
 * @property int    $user_id
 * @property int    $creator_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * Relationships
 *
 * @property User   $creator
 * @property User   $user
 */
class Note extends Model
{
    protected $table = 'user_notes';

    protected $fillable = ['content', 'user_id', 'creator_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
