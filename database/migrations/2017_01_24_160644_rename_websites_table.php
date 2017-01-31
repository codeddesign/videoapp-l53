<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_events', function (Blueprint $table) {
            $table->dropForeign('campaign_events_website_id_foreign');
        });

        Schema::rename('wordpress_sites', 'websites');

        Schema::table('campaign_events', function (Blueprint $table) {
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
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
            $table->dropForeign('campaign_events_website_id_foreign');
        });

        Schema::rename('websites', 'wordpress_sites');

        Schema::table('campaign_events', function (Blueprint $table) {
            $table->foreign('website_id')->references('id')->on('wordpress_sites')->onDelete('cascade');
        });
    }
}
