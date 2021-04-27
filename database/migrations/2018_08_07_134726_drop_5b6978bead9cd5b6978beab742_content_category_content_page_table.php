<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Drop5b6978bead9cd5b6978beab742ContentCategoryContentPageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('content_category_content_page');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('content_category_content_page')) {
            Schema::create('content_category_content_page', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('content_category_id')->unsigned()->nullable();
                $table->foreign('content_category_id', 'fk_p_193317_193319_conten_5b697831e5bd9')->references('id')->on('content_categories');
                $table->integer('content_page_id')->unsigned()->nullable();
                $table->foreign('content_page_id', 'fk_p_193319_193317_conten_5b697831e69b6')->references('id')->on('content_pages');

                $table->timestamps();
            });
        }
    }
}
