<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('order');
            $table->primary(['id', 'order']);
            $table->text('text')->nullable();
            $table->timestamps();
        });

        Schema::create('answer_question', function (Blueprint $table) {
            $table->integer('question_id');
            $table->integer('answer_id');
            $table->primary(['question_id', 'answer_id']);
            $table->timestamps();
            // $table->foreign('question_id')
            //     ->references('id')->on('questions')
            //     ->onDelete('cascade');
            // $table->foreign('answer_id')
            //     ->references('id')->on('answers')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('answer_question');
    }
}
