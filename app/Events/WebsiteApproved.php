<?php

namespace App\Events;

use App\Models\Website;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class WebsiteApproved
{
    use Dispatchable, SerializesModels;

    /**
     * @var \App\Models\Website
     */
    public $website;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }
}
