<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5b696c93350caRelationshipsToSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function(Blueprint $table) {
            if (!Schema::hasColumn('surveys', 'institution_id')) {
                $table->integer('institution_id')->unsigned()->nullable();
                $table->foreign('institution_id', '193284_5b696b83b699e')->references('id')->on('institutions')->onDelete('cascade');
                }
                if (!Schema::hasColumn('surveys', 'group_id')) {
                $table->integer('group_id')->unsigned()->nullable();
                $table->foreign('group_id', '193284_5b696c92c28a3')->references('id')->on('groups')->onDelete('cascade');
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
        Schema::table('surveys', function(Blueprint $table) {
            
        });
    }
}
