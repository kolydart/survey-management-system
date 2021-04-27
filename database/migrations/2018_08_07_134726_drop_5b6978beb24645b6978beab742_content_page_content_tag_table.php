<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Drop5b6978beb24645b6978beab742ContentPageContentTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('content_page_content_tag');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('content_page_content_tag')) {
            Schema::create('content_page_content_tag', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('content_page_id')->unsigned()->nullable();
                $table->foreign('content_page_id', 'fk_p_193319_193318_conten_5b69783241424')->references('id')->on('content_pages');
                $table->integer('content_tag_id')->unsigned()->nullable();
                $table->foreign('content_tag_id', 'fk_p_193318_193319_conten_5b6978324073b')->references('id')->on('content_tags');

                $table->timestamps();
            });
        }
    }
}
