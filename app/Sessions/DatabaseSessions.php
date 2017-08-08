<?php

namespace App\Sessions;

use App\Models\SessionEvent;

class DatabaseSessions
{
    protected $sessions;

    public function fetch($timeRange, $timezone = null)
    {
        $sessions = new SessionsCollection;

        return $sessions
            ->merge(SessionEvent::timeRange($timeRange, $timezone)->get()->toArray())
            ->values();
    }
}
