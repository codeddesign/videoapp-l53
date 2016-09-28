<?php

namespace App\Listeners;

use App\Events\VerifyAccount;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
