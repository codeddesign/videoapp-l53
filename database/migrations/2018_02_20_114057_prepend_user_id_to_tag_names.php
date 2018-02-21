<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrependUserIdToTagNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tags   = DB::table('tags')->get();

        foreach ($tags as $tag) {
            if($tag->user_id) {
                DB::table('tags')->where('id', $tag->id)->update([
                    'description' => $tag->user_id . '_' . $tag->description
                ]);
            }
        }
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
