<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update1533799016SurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            if(Schema::hasColumn('surveys', 'group_id')) {
                $table->dropForeign('193284_5b696c92c28a3');
                $table->dropIndex('193284_5b696c92c28a3');
                $table->dropColumn('group_id');
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
