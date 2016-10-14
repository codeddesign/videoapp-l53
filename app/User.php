<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Campaign;
use App\Models\CampaignType;
use App\Models\Wordpress;
use App\Services\Youtube;

/**
 * @author Coded Design
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
        'verified_phone' => 'boolean',
    ];

    /**
     * Encrypt password by default.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

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

    /**
     * A user may have many wordpress sites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordpressSites()
    {
        return $this->hasMany(Wordpress::class);
    }

    // @todo refactor this
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

        if (! $toSession) {
            $campaign->save();
        }

        // add campaign videos
        $campaign->videos = $campaign->addVideos($data, $toSession);

        return $campaign;
    }
}
