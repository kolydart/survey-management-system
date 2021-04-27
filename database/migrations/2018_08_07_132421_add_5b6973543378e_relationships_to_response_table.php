<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Add5b6973543378eRelationshipsToResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responses', function (Blueprint $table) {
            if (! Schema::hasColumn('responses', 'question_id')) {
                $table->integer('question_id')->unsigned()->nullable();
                $table->foreign('question_id', '193304_5b697353bc47b')->references('id')->on('questions')->onDelete('cascade');
            }
            if (! Schema::hasColumn('responses', 'answer_id')) {
                $table->integer('answer_id')->unsigned()->nullable();
                $table->foreign('answer_id', '193304_5b697353ca6cc')->references('id')->on('answers')->onDelete('cascade');
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
        Schema::table('responses', function (Blueprint $table) {
        });
    }
}
