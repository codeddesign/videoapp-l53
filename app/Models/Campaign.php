<?php

namespace VideoAd\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Adib Hanna <adibhanna@gmail.com>
 * Class Campaign
 * @package VideoAd\Models
 */
class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'campaigns';

    /**
     * The allowed field.
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id', 'campaign_type_id', 'rpm', 'size'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rpm' => 'integer',
    ];

    /**
     * A campaign belongs to a campaign type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(CampaignType::class, 'campaign_type_id');
    }
}
