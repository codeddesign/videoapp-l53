<?php

namespace App\CampaignEvents\Facades;

use Illuminate\Support\Facades\Facade;

class CampaignEvent extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return \App\CampaignEvents\CampaignEventInterface::class;
    }
}
