<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create5b4888ab2f1c6CategorySurveyTable extends Migration
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
                $table->foreign('category_id', 'fk_p_8163_8158_survey_cat_5b4888ab2f28f')->references('id')->on('categories')->onDelete('cascade');
                $table->integer('survey_id')->unsigned()->nullable();
                $table->foreign('survey_id', 'fk_p_8158_8163_category_s_5b4888ab2f2ea')->references('id')->on('surveys')->onDelete('cascade');
                
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
