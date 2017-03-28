<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorAdType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE tags RENAME COLUMN ad_type TO type');
        \DB::statement('ALTER TABLE tags RENAME COLUMN campaign_types TO ad_types');

        DB::table('tags')
            ->update(['ad_types' => json_encode([1, 2])]);

        Schema::table('campaign_types', function (Blueprint $table) {
            $table->dropColumn('alias');
            $table->dropColumn('single');
            $table->integer('ad_type_id')->default(1)->unsigned();
        });

        \DB::statement('ALTER TABLE backfill RENAME COLUMN ad_type TO ad_type_id');

        DB::table('backfill')
            ->where('ad_type_id', 'onscroll')
            ->update(['ad_type_id' => '1']);

        DB::table('backfill')
            ->where('ad_type_id', 'infinity')
            ->update(['ad_type_id' => '2']);

        \DB::statement('ALTER TABLE backfill ALTER COLUMN ad_type_id TYPE integer USING (ad_type_id::integer);
');
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
