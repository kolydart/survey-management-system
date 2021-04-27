<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Add5b6989ec52707RelationshipsToQuestionnaireTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            if (! Schema::hasColumn('questionnaires', 'survey_id')) {
                $table->integer('survey_id')->unsigned()->nullable();
                $table->foreign('survey_id', '193301_5b6972476a3cf')->references('id')->on('surveys')->onDelete('cascade');
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
        Schema::table('questionnaires', function (Blueprint $table) {
        });
    }
}
