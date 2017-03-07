<?php

use Illuminate\Database\Migrations\Migration;

class ChangeReportsDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('reports')
            ->where('date_range', 'lastThreeDays')
            ->update(['date_range' => 'threeDays']);

        DB::table('reports')
            ->where('date_range', 'lastSevenDays')
            ->update(['date_range' => 'sevenDays']);

        DB::table('reports')
            ->where('date_range', 'lastThirtyDays')
            ->update(['date_range' => 'thirtyDays']);
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
