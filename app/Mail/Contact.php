<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $sender;

    /**
     * @var array
     */
    protected $receivers = [
        'ian@ad3media.com',
        'daniel@ad3media.com',
        'bryant@ad3media.com',
    ];

    /**
     * Create a new message instance.
     *
     * @param array $sender
     */
    public function __construct(array $sender)
    {
        $this->sender = $sender;

        $this->subject = $sender['name'].' says hello';
        if (!is_null($sender['website'])) {
            $this->subject = 'Inquiry request from '.$sender['name'];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject)
            ->to($this->receivers)
            ->view('emails.contact');
    }
}
