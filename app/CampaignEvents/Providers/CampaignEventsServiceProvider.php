<?php

namespace VideoAd\CampaignEvents\Providers;

use VideoAd\CampaignEvents\Manager;
use Illuminate\Support\ServiceProvider;
use VideoAd\CampaignEvents\CampaignEventInterface;
use VideoAd\CampaignEvents\Repositories\CampaignInterface;
use VideoAd\CampaignEvents\Repositories\CampaignRepository;

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
        // bind the CampaignEventInterface interface to the Manager class.
        $this->app->bind(CampaignEventInterface::class, Manager::class);

        $this->app->bind(CampaignInterface::class, CampaignRepository::class);
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
