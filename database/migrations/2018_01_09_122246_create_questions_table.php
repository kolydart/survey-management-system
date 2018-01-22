<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->string('template'); // temporary
            $table->string('paper_id'); // temporary
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('question_questionnaire', function (Blueprint $table) {
            $table->integer('questionnaire_id');
            $table->integer('question_id');
            $table->integer('answer_order');
            $table->text('response')->nullable();
            $table->timestamps();
            $table->primary(['questionnaire_id', 'question_id', 'answer_order'],'question_questionnaire_primary');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
        Schema::dropIfExists('question_questionnaire');
    }
}
