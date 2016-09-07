<?php

namespace VideoAd\CampaignEvents\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Coded Design
 * Class CampaignEvent
 * @package VideoAd\CampaignEvents\Facades
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
        return \VideoAd\CampaignEvents\CampaignEventInterface::class;
    }
}
