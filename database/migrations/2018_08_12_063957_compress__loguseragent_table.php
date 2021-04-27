<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompressLoguseragentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `loguseragents` ENGINE=InnoDB ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8;');
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
