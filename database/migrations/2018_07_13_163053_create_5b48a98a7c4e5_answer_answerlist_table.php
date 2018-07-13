<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create5b48a98a7c4e5AnswerAnswerlistTable extends Migration
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
                $table->foreign('answer_id', 'fk_p_8193_8192_answerlist_5b48a98a7c59e')->references('id')->on('answers')->onDelete('cascade');
                $table->integer('answerlist_id')->unsigned()->nullable();
                $table->foreign('answerlist_id', 'fk_p_8192_8193_answer_ans_5b48a98a7c5e6')->references('id')->on('answerlists')->onDelete('cascade');
                
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
