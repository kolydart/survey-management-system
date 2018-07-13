<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5b48a9e9abbdeRelationshipsToResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responses', function(Blueprint $table) {
            if (!Schema::hasColumn('responses', 'question_id')) {
                $table->integer('question_id')->unsigned()->nullable();
                $table->foreign('question_id', '8191_5b48a63aeae7c')->references('id')->on('questions')->onDelete('cascade');
                }
                if (!Schema::hasColumn('responses', 'answer_id')) {
                $table->integer('answer_id')->unsigned()->nullable();
                $table->foreign('answer_id', '8191_5b48a9e98a352')->references('id')->on('answers')->onDelete('cascade');
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
        Schema::table('responses', function(Blueprint $table) {
            
        });
    }
}
