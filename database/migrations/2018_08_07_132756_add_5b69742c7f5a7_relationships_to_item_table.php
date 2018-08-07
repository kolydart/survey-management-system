<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5b69742c7f5a7RelationshipsToItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function(Blueprint $table) {
            if (!Schema::hasColumn('items', 'survey_id')) {
                $table->integer('survey_id')->unsigned()->nullable();
                $table->foreign('survey_id', '193305_5b6974115a248')->references('id')->on('surveys')->onDelete('cascade');
                }
                if (!Schema::hasColumn('items', 'question_id')) {
                $table->integer('question_id')->unsigned()->nullable();
                $table->foreign('question_id', '193305_5b69741169862')->references('id')->on('questions')->onDelete('cascade');
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
        Schema::table('items', function(Blueprint $table) {
            
        });
    }
}
