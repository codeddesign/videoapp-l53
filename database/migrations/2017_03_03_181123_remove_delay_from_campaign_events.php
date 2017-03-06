<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class RemoveDelayFromCampaignEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $times = DB::table('campaign_events')->select('created_at')->distinct()->get()->pluck('created_at');

        foreach ($times as $timestamp) {
            $newTime    = (new Carbon($timestamp))->second(0)->subSecond();

            DB::table('campaign_events')
                ->where('created_at', $timestamp)
                ->update(['created_at' => $newTime]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
