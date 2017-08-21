<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Services\Youtube;

/**
 * Database Columns
 *
 * @property int        $id
 * @property string     $first_name
 * @property string     $last_name
 * @property string     $company
 * @property string     $email
 * @property string     $password
 * @property string     $phone_number
 * @property string     $street_line_1
 * @property string     $street_line_2
 * @property string     $city
 * @property string     $state
 * @property string     $country
 * @property string     $zip_code
 * @property array      $bank_details
 * @property string     $email_verification_token
 * @property bool       $verified_phone
 * @property bool       $verified_email
 * @property bool       $admin
 * @property bool       $active
 * @property string     $timezone
 * @property \DateTime  $created_at
 * @property \DateTime  $updated_at
 *
 * Relationships
 *
 * @property Collection $campaigns
 * @property Collection $reports
 * @property Collection $notes
 * @property Collection $websites
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Notifiable, SoftDeletes, Authenticatable, Authorizable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'email',
        'password',
        'phone_number',
        'address',
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
        'bank_details'   => 'array',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array
     */
    protected $encrypts = [
        'bank_details',
    ];

    /**
     * Hash the password by default.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Returns the state of the user, if both email and phone are verified.
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

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * A user may have many reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * A user may have many websites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    /**
     * Returns notes created about regarding this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function addCampaign(array $data, $toSession = false)
    {
        // add campaign
        $data['user_id'] = $this->id;

        $campaignType = CampaignType::where('id', $data['campaign_type_id'])->first();

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
