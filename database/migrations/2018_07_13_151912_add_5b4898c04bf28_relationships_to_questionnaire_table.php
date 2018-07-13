<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5b4898c04bf28RelationshipsToQuestionnaireTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaires', function(Blueprint $table) {
            if (!Schema::hasColumn('questionnaires', 'survey_id')) {
                $table->integer('survey_id')->unsigned()->nullable();
                $table->foreign('survey_id', '8176_5b4898c02b52e')->references('id')->on('surveys')->onDelete('cascade');
                }
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires', function(Blueprint $table) {
            
        });
    }
}
