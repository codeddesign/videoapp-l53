<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class RenameAdErrorsToErrors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('campaign_events')
            ->where('name', 'adErrors')
            ->update(['name' => 'errors']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('campaign_events')
            ->where('name', 'errors')
            ->update(['name' => 'adErrors']);
    }
}
