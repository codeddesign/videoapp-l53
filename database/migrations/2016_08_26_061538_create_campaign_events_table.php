<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_events', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('campaign_id')
                ->unsigned()
                ->references('id')
                ->on('campaigns');

            $table->string('name')->index();

            $table->string('event')->index();

            $table->string('referer')->default(null)->index();

            $table->string('ip')->default(null)->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('campaign_events');
    }
}
