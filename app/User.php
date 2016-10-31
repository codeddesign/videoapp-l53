<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Campaign;
use App\Models\CampaignType;
use App\Models\WordpressSite;
use App\Services\Youtube;

/**
 * Database Columns
 *
 * @property int        $id
 * @property string     $name
 * @property string     $email
 * @property string     $password
 * @property string     $email_verification_token
 * @property bool       $verified_phone
 * @property bool       $verified_email
 * @property \DateTime  $created_at
 * @property \DateTime  $updated_at
 *
 * Relationships
 *
 * @property Collection $campaigns
 * @property Collection $wordpressSites
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

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
        'email_verification_token',
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
        return $this->hasMany(WordpressSite::class);
    }

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

    /**
     * Sets verified phone to true.
     *
     * @return User
     */
    public function confirmPhone()
    {
        $this->verified_phone = true;
        $this->save();

        return $this;
    }

    /**
     * Sets verified email to true.
     *
     * @return User
     */
    public function confirmEmail()
    {
        $this->verified_email = true;
        $this->save();

        return $this;
    }
}
