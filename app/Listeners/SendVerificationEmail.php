<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use App\Mail\VerifyEmail;
use Illuminate\Mail\Mailer;

class SendVerificationEmail
{
    /**
     * @var \Illuminate\Mail\Mailer
     */
    private $mailer;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Mail\Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param AccountCreated $event
     */
    public function handle(AccountCreated $event)
    {
        $user = $event->user;

        $this->mailer->to($user)->send(new VerifyEmail($user));
    }
}
