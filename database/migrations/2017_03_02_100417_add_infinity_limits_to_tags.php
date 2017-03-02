<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfinityLimitsToTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->integer('infinity_timeout_limit')->nullable();
            $table->integer('infinity_wrapper_limit')->nullable();
            $table->integer('infinity_delay_time')->nullable();
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
            $table->dropColumn('infinity_timeout_limit');
            $table->dropColumn('infinity_wrapper_limit');
            $table->dropColumn('infinity_delay_time');
        });
    }
}
