<?php

namespace App\Transformers\Spreadsheet;

use App\Models\User;

class UserTransformer
{
    public function header()
    {
        return [
            'user_id',
            'name',
        ];
    }

    public function transform(User $user)
    {
        return [
            $user->id,
            "{$user->first_name} {$user->last_name}",
        ];
    }
}
