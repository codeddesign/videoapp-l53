<?php

namespace App\Listeners;

use App\Events\VerifyAccount;

class VerifyEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VerifyAccount  $event
     * @return void
     */
    public function handle(VerifyAccount $event)
    {
        // send verification email
    }
}
