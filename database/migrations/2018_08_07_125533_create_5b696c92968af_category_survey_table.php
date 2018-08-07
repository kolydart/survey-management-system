<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create5b696c92968afCategorySurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('category_survey')) {
            Schema::create('category_survey', function (Blueprint $table) {
                $table->integer('category_id')->unsigned()->nullable();
                $table->foreign('category_id', 'fk_p_193219_193284_survey_5b696c9296a1d')->references('id')->on('categories')->onDelete('cascade');
                $table->integer('survey_id')->unsigned()->nullable();
                $table->foreign('survey_id', 'fk_p_193284_193219_catego_5b696c9296af2')->references('id')->on('surveys')->onDelete('cascade');
                
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
        Schema::dropIfExists('category_survey');
    }
}
