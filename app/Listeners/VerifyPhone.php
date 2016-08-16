<?php

namespace VideoAd\Listeners;

use VideoAd\Events\VerifyAccount;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyPhone
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
        // send verification sms
    }
}
