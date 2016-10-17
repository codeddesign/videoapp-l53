<?php

namespace App\CampaignEvents\Providers;

use App\CampaignEvents\Manager;
use Illuminate\Support\ServiceProvider;
use App\CampaignEvents\CampaignEventInterface;
use App\CampaignEvents\Repositories\CampaignInterface;
use App\CampaignEvents\Repositories\CampaignRepository;

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
