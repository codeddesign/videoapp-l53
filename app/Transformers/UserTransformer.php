<?php

namespace App\Transformers;

use App\User;

class UserTransformer extends Transformer
{
    protected $availableIncludes = [
        'campaigns',
    ];

    public function transform(User $user)
    {
        return [
            'id'                => (int) $user->id,
            'first_name'        => $user->first_name,
            'last_name'         => $user->last_name,
            'company'           => $user->company,
            'email'             => $user->email,
            'isAdmin'           => (bool) $user->admin,
            'active'            => (bool) $user->active,
            'created_at'        => $user->created_at->getTimestamp(),
            'created_at_humans' => $user->created_at->diffForHumans(),
        ];
    }

    public function includeCampaigns(User $user)
    {
        $campaigns = $user->campaigns;

        return $this->collection($campaigns, new CampaignTransformer);
    }
}
