<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Database Columns
 *
 * @property int        $id
 * @property string     $title
 * @property string     $alias
 * @property bool       $available
 * @property bool       $single
 * @property bool       $has_name
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 *
 * Relationships
 *
 * @property Collection $campaigns
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
        'single'    => 'boolean',
        'has_name'  => 'boolean',
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
