<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update1531489940SurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            if(Schema::hasColumn('surveys', 'introduction')) {
                $table->dropColumn('introduction');
            }
            
        });
Schema::table('surveys', function (Blueprint $table) {
            
if (!Schema::hasColumn('surveys', 'introduction')) {
                $table->text('introduction')->nullable();
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
            
        });
Schema::table('surveys', function (Blueprint $table) {
                        $table->string('introduction')->nullable();
                
        });

    }
}
