<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update1533635730SurveysTable extends Migration
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
                $table->text('introduction')->nullable();
                }
if (!Schema::hasColumn('surveys', 'notes')) {
                $table->text('notes')->nullable();
                }
if (!Schema::hasColumn('surveys', 'access')) {
                $table->string('access')->nullable();
                }
if (!Schema::hasColumn('surveys', 'completed')) {
                $table->tinyInteger('completed')->nullable()->default('0');
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
            $table->dropColumn('access');
            $table->dropColumn('completed');
            
        });

    }
}
