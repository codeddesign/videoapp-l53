<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\CampaignEvent;
use App\Models\Note;
use App\Stats\Calculator;
use App\Transformers\NoteTransformer;
use App\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends ApiController
{
    public function index()
    {
        $accounts = User::with('campaigns')->get();

        $events = CampaignEvent::with('tag', 'website.user')
            ->select('campaign_id', 'tag_id', DB::raw('SUM(count) as count'))
            ->where('name', 'impressions')
            ->where('tag_id', '!=', null)
            ->where('campaign_id', '!=', null)
            ->timeRange('thirtyDays')
            ->groupBy('campaign_id', 'tag_id')
            ->get()
            ->groupBy('campaign_id');

        //Calculate revenue per campaign
        $campaignsRevenue = $events->map(function ($campaignEvents) {
            return $campaignEvents->sum(function ($event) {
                return Calculator::revenue($event->count, $event->tag);
            });
        });

        //Sum the users campaigns revenue
        $accounts->map(function ($account) use ($campaignsRevenue) {
            $account->revenue = $account->campaigns->pluck('id')->sum(function ($id) use ($campaignsRevenue) {
                return $campaignsRevenue[$id] ?? 0;
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

        return $this->itemResponse($user, new UserTransformer);
    }

    public function addNote($id, Request $request)
    {
        $user = User::findOrFail($id);

        $note = new Note;
        $note->user_id = $user->id;
        $note->creator_id = $this->user->id;
        $note->content = $request->get('content');
        $note->save();

        return $this->itemResponse($note, new NoteTransformer);
    }
}
