<?php

namespace App\CampaignEvents\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Coded Design
 *
 * @package App\CampaignEvents\Facades
 */
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
