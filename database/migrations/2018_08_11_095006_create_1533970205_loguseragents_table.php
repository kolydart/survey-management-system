<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create1533970205LoguseragentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('loguseragents')) {
            Schema::create('loguseragents', function (Blueprint $table) {
                $table->increments('id');
                $table->string('os')->nullable();
                $table->string('os_version')->nullable();
                $table->string('browser')->nullable();
                $table->string('browser_version')->nullable();
                $table->string('device')->nullable();
                $table->string('language')->nullable();
                $table->integer('item_id')->nullable()->unsigned();
                $table->string('ipv6')->nullable();
                $table->string('uri')->nullable();
                $table->tinyInteger('form_submitted')->nullable()->default('0');

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
        Schema::dropIfExists('loguseragents');
    }
}
