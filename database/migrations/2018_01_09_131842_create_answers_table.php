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
        Schema::create('tmp_answers', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('order');
            $table->primary(['id', 'order']);
            $table->text('text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tmp_answer_question', function (Blueprint $table) {
            $table->integer('question_id');
            $table->integer('answer_id');
            $table->primary(['question_id', 'answer_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmp_answers');
        Schema::dropIfExists('tmp_answer_question');
    }
}
