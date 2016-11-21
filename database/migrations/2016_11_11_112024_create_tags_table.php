<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->string('advertiser');
            $table->string('description');
            $table->string('platform_type');
            $table->string('ad_type');
            $table->boolean('date_range');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('timeout_limit');
            $table->integer('wrapper_limit');
            $table->jsonb('campaign_types');
            $table->integer('delay_time');
            $table->integer('daily_request_limit');
            $table->integer('priority_count');
            $table->integer('guarantee_limit');
            $table->integer('guarantee_order');
            $table->boolean('guarantee_enabled')->default(false);
            $table->integer('ecpm');
            $table->boolean('active')->default(true);
            $table->jsonb('included_locations');
            $table->jsonb('excluded_locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
    }
}
