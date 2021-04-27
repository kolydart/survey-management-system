<?php

use App\Answer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHiddenToAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** add field to schema */
        Schema::table('answers', function (Blueprint $table) {
            $table->boolean('hidden')->default(false);
        });

        /** create a single record */
        Answer::create(['title'=>'value', 'hidden'=>true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('hidden');
        });
    }
}
