<?php

namespace VideoAd;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use VideoAd\Models\Campaign;
use VideoAd\Models\CampaignType;
use VideoAd\Services\Youtube;

/**
 * @author Coded Design
 * Class User
 * @package VideoAd
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified_email',
        'verified_phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'verified_email',
        'verified_phone',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_email' => 'boolean',
        'verified_phone' => 'boolean'
    ];

    /**
     * return the state of the user, if both email and phone are verified.
     *
     * @return bool
     */
    public function verified()
    {
        return $this->verified_email || $this->verified_phone;
    }

    /**
     * A user may have many campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    // @todo remove this from here.
    public function addCampaign(array $data, $toSession = false)
    {
        // add campaign
        $data['user_id'] = $this->id;

        $campaignType = CampaignType::where('alias', $data['type'])->first();
        $data['campaign_type_id'] = $campaignType->id;

        if (! $campaignType->has_name) {
            $data['name'] = Youtube::title($data);
        }

        $campaign = new Campaign();
        $campaign->fill($data);

        if (!$toSession) {
            $campaign->save();
        }

        // add campaign videos
        $campaign->videos = $campaign->addVideos($data, $toSession);

        return $campaign;
    }
}
