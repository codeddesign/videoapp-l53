<?php

namespace App\Mail;

use App\Models\Signup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BetaSignup extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Signup
     */
    public $signup;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Signup $signup
     */
    public function __construct(Signup $signup)
    {
        $this->signup = $signup;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.signup');
    }
}
