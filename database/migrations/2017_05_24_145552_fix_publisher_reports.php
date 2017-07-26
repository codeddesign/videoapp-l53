<?php

use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class FixPublisherReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $reports = DB::table('reports')->get();
        $users   = DB::table('users')->get();

        $reportsToUpdate = [];

        foreach ($reports as $report) {
            $user    = $users->where('id', $report->user_id)->first();
            $isAdmin = $user->admin;

            if (! $isAdmin) {
                $reportsToUpdate[] = $report->id;
            }
        }

        DB::table('reports')->whereIn('id', $reportsToUpdate)->update([
            'combine_by'       => 'website',
            'sort_by'          => 'website',
            'included_metrics' => json_encode(Report::allUserMetrics()),
        ]);
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
