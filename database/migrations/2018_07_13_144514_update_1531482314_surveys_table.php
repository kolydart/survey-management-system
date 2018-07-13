<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update1531482314SurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            
if (!Schema::hasColumn('surveys', 'introduction')) {
                $table->string('introduction')->nullable();
                }
if (!Schema::hasColumn('surveys', 'notes')) {
                $table->string('notes')->nullable();
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
            $table->dropColumn('introduction');
            $table->dropColumn('notes');
            
        });

    }
}
