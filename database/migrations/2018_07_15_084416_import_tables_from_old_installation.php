<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportTablesFromOldInstallation extends Migration
{
    /**
     * Run the migrations.
     * Seed tmp_tables with data from old installation
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('db:seed', ['--class' => tlpmSeeder::class ]);
        Artisan::call('db:seed', ['--class' => prevezaMusicSchoolSeeder::class ]);
        Artisan::call('db:seed', ['--class' => createSurveysSeeder::class ]);
        Artisan::call('db:seed', ['--class' => incrementSeeder::class ]);
        // Artisan::call('db:seed', ['--class' => moveFieldsSeeder::class ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
