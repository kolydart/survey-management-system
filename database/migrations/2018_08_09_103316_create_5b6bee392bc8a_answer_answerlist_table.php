<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create5b6bee392bc8aAnswerAnswerlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('answer_answerlist')) {
            Schema::create('answer_answerlist', function (Blueprint $table) {
                $table->integer('answer_id')->unsigned()->nullable();
                $table->foreign('answer_id', 'fk_p_193274_193270_answer_5b6bee392bd95')->references('id')->on('answers')->onDelete('cascade');
                $table->integer('answerlist_id')->unsigned()->nullable();
                $table->foreign('answerlist_id', 'fk_p_193270_193274_answer_5b6bee392be44')->references('id')->on('answerlists')->onDelete('cascade');
                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_answerlist');
    }
}
