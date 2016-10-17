<?php

namespace App\CampaignEvents\Entities;

class Event
{
    /**
     * Instantiate an instance of this class.
     *
     * @param array $response
     * @return static
     */
    public function make(array $response)
    {
        $event = new static;

        // fill the response data.
        $this->fill($response);

        // return the event object.
        return $event;
    }

    /**
     * Fill the data of the event.
     *
     * @param $response
     */
    private function fill($response)
    {
        $this->campaign_id = $response->campaign_id;
        $this->name = $response->name;
        $this->type = $response->event;
    }
}
