<?php

namespace App\Mail;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebsiteApprovedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $website;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.website-approved')
            ->subject('Your website has been approved');
    }
}
