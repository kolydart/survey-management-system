<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5b48a63b17c58RelationshipsToResponseTable extends Migration
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
