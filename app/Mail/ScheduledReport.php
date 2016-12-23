<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\File\File;

class ScheduledReport extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    private $file;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Report                          $report
     * @param \Symfony\Component\HttpFoundation\File\File $file
     */
    public function __construct(Report $report, File $file)
    {
        $this->report = $report;
        $this->file   = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.report')
            ->attach($this->file->getRealPath(), [
                'as'   => $this->report->friendlyFilename(),
                'mime' => $this->file->getMimeType(),
            ]);
    }
}
