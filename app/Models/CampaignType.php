<?php

namespace VideoAd\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Coded Design
 * Class CampaignType
 * @package VideoAd\Models
 */
class CampaignType extends Model
{
    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'campaign_types';

    /**
     * The allowed fields.
     *
     * @var array
     */
    protected $fillable = ['title', 'alias', 'available', 'single', 'has_name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'available' => 'boolean',
        'single' => 'boolean',
        'has_name' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * A campaign type can have many campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
