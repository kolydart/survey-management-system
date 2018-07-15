<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('institution');
            $table->string('subject');
            $table->text('intro');
            $table->text('notes');
            $table->string('person')->default('Κολυδάς, Τάσος');
            $table->dateTime('date_begin');
            $table->dateTime('date_end');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('question_survey', function (Blueprint $table) {
            $table->integer('survey_id');
            $table->integer('question_id');
            $table->string('code')->nullable()->comment('Ο κωδικός του ερωτήματος');
            $table->primary(['survey_id', 'question_id']);            
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
        Schema::dropIfExists('surveys');
        Schema::dropIfExists('question_survey');
    }
}