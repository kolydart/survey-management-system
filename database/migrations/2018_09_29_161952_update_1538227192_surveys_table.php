<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update1538227192SurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            
if (!Schema::hasColumn('surveys', 'alias')) {
                $table->string('alias');
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
            $table->dropColumn('alias');
            
        });

    }
}
