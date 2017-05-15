<?php

namespace App\Listeners;

use App\Events\WebsiteApproved;
use App\Mail\WebsiteApprovedEmail;
use Illuminate\Mail\Mailer;

class SendApprovalEmail
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
     * @param  WebsiteApproved  $event
     * @return void
     */
    public function handle(WebsiteApproved $event)
    {
        $website = $event->website;
        $user = $website->user;

        $this->mailer->to($user)->send(new WebsiteApprovedEmail($website));
    }
}
