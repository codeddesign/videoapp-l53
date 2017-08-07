<?php

namespace App\Jobs;

use App\Models\SessionEvent;
use App\Services\AnalyticsEvents;
use App\Services\CampaignEvents;
use Illuminate\Bus\Queueable;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PersistEvents extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->lockJob()) {
            return;
        }

        $campaignEvents      = new CampaignEvents;
        $savedCampaignEvents = $campaignEvents->persistRedisData();

        $analyticsEvents      = new AnalyticsEvents;
        $savedAnalyticsEvents = $analyticsEvents->persistRedisData();

        $totalEvents = $savedCampaignEvents->sum('count') + $savedAnalyticsEvents->sum('count');

        $message = "{$totalEvents} events saved.";
        $this->log($message);

        $sessions = SessionEvent::persist();
        $message  = $sessions->sum('sessions').' sessions saved.';
        $this->log($message);

        $cache = app(Repository::class);
        $cache->tags(['events'])->flush();
    }
}
