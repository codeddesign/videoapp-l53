<?php

namespace VideoAd\CampaignEvents\Providers;

use VideoAd\CampaignEvents\Manager;
use Illuminate\Support\ServiceProvider;
use VideoAd\CampaignEvents\CampaignEvent;

/**
 * @author Coded Design
 * Class CampaignEventsServiceProvider
 * @package VideoAd\CampaignEvents\Providers
 */
class CampaignEventsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // bind the CampaignEvent interface to the Manager class.
        $this->app->bind(CampaignEvent::class, Manager::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
