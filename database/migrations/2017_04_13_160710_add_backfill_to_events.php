<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBackfillToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_events', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->integer('backfill_id')->unsigned()->nullable();
            $table->foreign('backfill_id')->references('id')->on('backfill')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_events', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->dropColumn('backfill_id');
        });
    }
}
