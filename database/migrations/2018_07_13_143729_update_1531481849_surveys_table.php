<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update1531481849SurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            if(Schema::hasColumn('surveys', 'class_id')) {
                $table->dropForeign('8158_5b4888ab39088');
                $table->dropIndex('8158_5b4888ab39088');
                $table->dropColumn('class_id');
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
        Schema::table('surveys', function (Blueprint $table) {
                        
        });

    }
}
