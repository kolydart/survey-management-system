<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5b488f1e516beRelationshipsToSurveyTable extends Migration
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
