<?php

namespace App\CampaignEvents\Repositories;

/**
 * @author Coded Design
 * Interface CampaignInterface
 * @package App\CampaignEvents\Repositories
 */
interface CampaignInterface
{
    /**
     * Find a campaign by a given attribute.
     *
     * @param $attribute
     * @param $value
     * @param null $relations
     * @param bool $withTrashed
     * @return Collection
     */
    public function findBy($attribute, $value, $relations = null, $withTrashed = false);

    /**
     * Find a campaign by a given attribute with the trashed campaigns.
     *
     * @param $attribute
     * @param $value
     * @param null $relations
     * @return Collection
     */
    public function findByWithTrashed($attribute, $value, $relations = null);
}
