<?php

namespace App\Transformers;

use App\Models\Note;

class NoteTransformer extends Transformer
{
    public function transform(Note $note)
    {
        return [
            'content'           => $note->content,
            'user_id'           => $note->user_id,
            'creator_id'        => $note->creator_id,
            'creator_name'      => "{$note->creator->first_name} {$note->creator->last_name}",
            'created_at'        => $this->date($note->created_at),
            'created_at_humans' => $note->created_at->diffForHumans(),
        ];
    }
}
