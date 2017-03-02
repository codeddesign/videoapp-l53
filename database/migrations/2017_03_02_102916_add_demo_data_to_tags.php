<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDemoDataToTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->jsonb('demo_data')->default(json_encode([
                'platform_type'        => 'all',
                'ad_type'              => 'all',
                'campaign_type'        => 'all',
                'timeout_limit'        => '',
                'wrapper_limit'        => '',
                'delay_time'           => '',
                'session_max_requests' => '',
            ]));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('demo_data');
        });
    }
}
