<?php

namespace VideoAd\CampaignEvents;

use Illuminate\Database\Eloquent\Collection;
use VideoAd\Models\Campaign;

/**
 * @author Coded Design
 * Class CampaignRepository
 * @package VideoAd\CampaignEvents
 */
class CampaignRepository
{
    /**
     * @var Campaign
     */
    protected $campaign;

    /**
     * CampaignRepository constructor.
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Find a campaign by a given attribute.
     *
     * @param $attribute
     * @param $value
     * @param null $relations
     * @param bool $withTrashed
     * @return Collection
     */
    public function findBy($attribute, $value, $relations = null, $withTrashed = false)
    {
        if ($relations and is_array($relations))
        {
            $query = $this->campaign->where($attribute, $value);

            foreach($relations as $relation)
            {
                $query->with($relation);
            }

            if($withTrashed == true) {
                $query = $query->withTrashed();
            }

            return $query->first();
        }

        return $this->campaign->where($attribute, $value)->first();
    }

    /**
     * Find a campaign by a given attribute with the trashed campaigns.
     *
     * @param $attribute
     * @param $value
     * @param null $relations
     * @return Collection
     */
    public function findByWithTrashed($attribute, $value, $relations = null)
    {
        if ($relations and is_array($relations))
        {
            $query = $this->campaign->where($attribute, $value);

            foreach($relations as $relation)
            {
                $query->with($relation);
            }

            $query = $query->withTrashed();

            return $query->first();
        }

        return $this->campaign->where($attribute, $value)->first();
    }
}
