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
        $transformedUser = [
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
            'timezone'          => $user->timezone,
            'created_at'        => $user->created_at->getTimestamp(),
            'created_at_humans' => $user->created_at->diffForHumans(),
        ];

        if ($user->revenue !== null) {
            $transformedUser = array_merge($transformedUser, [
                'revenue' => $user->revenue,
            ]);
        }

        return $transformedUser;
    }

    public function includeCampaigns(User $user)
    {
        $campaigns = $user->campaigns;

        return $this->collection($campaigns, new CampaignTransformer);
    }

    public function includeWebsites(User $user)
    {
        $websites = $user->websites;

        return $this->collection($websites, new WebsiteTransformer);
    }

    public function includeNotes(User $user)
    {
        $notes = $user->notes->sortByDesc('created_at');

        return $this->collection($notes, new NoteTransformer);
    }
}
