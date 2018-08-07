<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1533645600ActivitylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('activitylogs')) {
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
                $table->softDeletes();

                $table->index(['deleted_at']);
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
