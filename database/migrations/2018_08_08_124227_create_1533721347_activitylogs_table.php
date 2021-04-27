<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create1533721347ActivitylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('activitylogs')) {
            Schema::create('activitylogs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('log_name')->nullable();
                $table->string('causer_type')->nullable();
                $table->integer('causer_id')->nullable();
                $table->string('description')->nullable();
                $table->string('subject_type')->nullable();
                $table->integer('subject_id')->nullable()->unsigned();
                $table->text('properties')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activitylogs');
    }
}
