<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Drop5b6bee07755cb5b6bee07733cdAnswerAnswerlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('answer_answerlist');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('answer_answerlist')) {
            Schema::create('answer_answerlist', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('answer_id')->unsigned()->nullable();
                $table->foreign('answer_id', 'fk_p_193274_193270_answer_5b696a4fcc56f')->references('id')->on('answers');
                $table->integer('answerlist_id')->unsigned()->nullable();
                $table->foreign('answerlist_id', 'fk_p_193270_193274_answer_5b696a4fcb8d3')->references('id')->on('answerlists');

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }
}
