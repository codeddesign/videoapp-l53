<?php

namespace App\Jobs;

use App\Services\AnalyticsEvents;
use App\Services\CampaignEvents;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PersistEvents extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaignEvents = new CampaignEvents;
        $events = $campaignEvents->persistRedisData();

        $analyticsEvents = new AnalyticsEvents;
        $events->merge($analyticsEvents->persistRedisData());

        $message = "{$events->sum('count')} events saved.";

        $this->log($message);
    }

}
