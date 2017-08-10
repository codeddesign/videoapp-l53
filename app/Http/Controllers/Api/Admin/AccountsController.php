<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Admin\StoreAccountRequest;
use App\Mail\AccountCreated;
use App\Models\CampaignEvent;
use App\Models\Note;
use App\Models\Website;
use App\Stats\Calculator;
use App\Transformers\NoteTransformer;
use App\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AccountsController extends ApiController
{
    public function index()
    {
        $eagerLoads = array_merge(
            $this->eagerLoads(),
            ['campaigns.type', 'campaigns.type.adType', 'websites.user']
        );

        $accounts = User::with($eagerLoads)->get();

        $websitesRevenue = Cache::remember('admin.revperuser', 60, function () {
            $events = CampaignEvent::with('tag', 'website.user')
                ->select('website_id', 'tag_id', DB::raw('SUM(count) as count'))
                ->where('name', 'impressions')
                ->where('tag_id', '!=', null)
                ->where('website_id', '!=', null)
                ->timeRange('thirtyDays', $this->user->timezone)
                ->groupBy('website_id', 'tag_id')
                ->get()
                ->groupBy('website_id');

            //Calculate revenue per website
            return $events->map(function ($campaignEvents) {
                return $campaignEvents->sum(function ($event) {
                    return Calculator::revenue($event->count, $event->tag);
                });
            });
        });

        //Sum the users websites revenue
        $accounts->map(function ($account) use ($websitesRevenue) {
            $account->revenue = $account->websites->pluck('id')->sum(function ($id) use ($websitesRevenue) {
                return $websitesRevenue[$id] ?? 0;
            });

            return $account;
        });

        return $this->collectionResponse($accounts, new UserTransformer);
    }

    public function show($id)
    {
        $account = User::where('id', $id)->with('websites');

        return $account;
    }

    public function activate($id, Request $request)
    {
        $user = User::findOrFail($id);

        $user->active = $request->get('status');
        $user->save();

        $cache = app(Repository::class);
        $cache->tags(['campaigns'])->flush();

        return $this->itemResponse($user, new UserTransformer);
    }

    public function addNote($id, Request $request)
    {
        $user = User::findOrFail($id);

        $note             = new Note;
        $note->user_id    = $user->id;
        $note->creator_id = $this->user->id;
        $note->content    = $request->get('content');
        $note->save();

        return $this->itemResponse($note, new NoteTransformer);
    }

    public function store(StoreAccountRequest $request, Mailer $mailer)
    {
        //$password = str_random(12);
        $password = 'mypassword';

        $user                 = new User();
        $user->first_name     = $request->get('first_name');
        $user->last_name      = $request->get('last_name');
        $user->company        = $request->get('company');
        $user->email          = $request->get('email');
        $user->password       = $password;
        $user->phone_number   = $request->get('phone_number');
        $user->street_line_1  = $request->get('street_line_1');
        $user->street_line_2  = $request->get('street_line_2');
        $user->city           = $request->get('city');
        $user->state          = $request->get('state');
        $user->country        = $request->get('country');
        $user->zip_code       = $request->get('zip_code');
        $user->timezone       = 'America/New_York';
        $user->verified_email = true;
        $user->verified_phone = true;
        $user->active         = true;

        $user->save();

        foreach ($request->get('websites') as $data) {
            $website          = new Website();
            $website->user_id = $user->id;
            $website->setDomainAttribute($data['url']);
            $website->owned    = $data['owned'];
            $website->approved = true;
            $website->waiting  = false;
            $website->save();
        }

        $mailer->to($user)->send(new AccountCreated($user, $password));

        return $this->itemResponse($user, new UserTransformer());
    }
}
