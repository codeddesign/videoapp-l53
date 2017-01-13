<?php

use Illuminate\Database\Migrations\Migration;

class ChangeTagsUrlType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE tags ALTER COLUMN url TYPE TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE tags ALTER COLUMN url TYPE VARCHAR');
    }
}
