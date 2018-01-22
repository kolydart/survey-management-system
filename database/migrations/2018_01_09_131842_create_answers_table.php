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
            $table->softDeletes();
        });

        Schema::create('answer_question', function (Blueprint $table) {
            $table->integer('question_id');
            $table->integer('answer_order');
            $table->primary(['question_id', 'answer_order']);
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
        Schema::dropIfExists('answers');
        Schema::dropIfExists('answer_question');
    }
}
