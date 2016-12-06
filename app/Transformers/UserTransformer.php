<?php

namespace App\Transformers;

use App\Models\User;
use stdClass;

class UserTransformer extends Transformer
{
    protected $availableIncludes = [
        'campaigns',
        'websites',
        'notes',
    ];

    public function transform(User $user)
    {
        return [
            'id'                => (int) $user->id,
            'first_name'        => $user->first_name,
            'last_name'         => $user->last_name,
            'company'           => $user->company,
            'email'             => $user->email,
            'phone_number'      => $user->phone_number,
            'address'           => $user->address,
            'bank_details'      => $user->bank_details ?? new stdClass(),
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

    public function includeWebsites(User $user)
    {
        $websites = $user->wordpressSites;

        return $this->collection($websites, new WordpressSiteTransformer);
    }

    public function includeNotes(User $user)
    {
        $notes = $user->notes->sortByDesc('created_at');

        return $this->collection($notes, new NoteTransformer);
    }
}
