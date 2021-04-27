<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Add5b6bec7204eebRelationshipsToQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            if (! Schema::hasColumn('questions', 'answerlist_id')) {
                $table->integer('answerlist_id')->unsigned()->nullable();
                $table->foreign('answerlist_id', '193283_5b6bec719f881')->references('id')->on('answerlists')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
        });
    }
}
