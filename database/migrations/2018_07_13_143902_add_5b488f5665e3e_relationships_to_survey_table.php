<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5b488f5665e3eRelationshipsToSurveyTable extends Migration
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
                $table->foreign('institution_id', '8158_5b4888ab34c98')->references('id')->on('institutions')->onDelete('cascade');
                }
                if (!Schema::hasColumn('surveys', 'group_id')) {
                $table->integer('group_id')->unsigned()->nullable();
                $table->foreign('group_id', '8158_5b488f5643b89')->references('id')->on('groups')->onDelete('cascade');
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
