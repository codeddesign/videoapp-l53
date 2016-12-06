<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Predis\Client;

trait AdminChannels
{
    public function getAdminChannels()
    {
        /** @var Client $redis */
        $redis = app('redis');

        if (empty($adminIds = $redis->hgetall('admin_ids'))) {
            $adminIds = User::where('admin', true)->pluck('id')->toArray();
            $redis->hmset('admin_ids', $adminIds);
        }

        $channels = [];

        foreach ($adminIds as $id) {
            $channels[] = new PrivateChannel('user.'.$id);
        }

        return $channels;
    }
}
