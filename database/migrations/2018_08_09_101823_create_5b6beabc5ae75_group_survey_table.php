<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create5b6beabc5ae75GroupSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('group_survey')) {
            Schema::create('group_survey', function (Blueprint $table) {
                $table->integer('group_id')->unsigned()->nullable();
                $table->foreign('group_id', 'fk_p_193220_193284_survey_5b6beabc5af99')->references('id')->on('groups')->onDelete('cascade');
                $table->integer('survey_id')->unsigned()->nullable();
                $table->foreign('survey_id', 'fk_p_193284_193220_group__5b6beabc5b02a')->references('id')->on('surveys')->onDelete('cascade');
                
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
        Schema::dropIfExists('group_survey');
    }
}
