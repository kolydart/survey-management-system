<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create5b6978f492725ContentPageContentTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('content_page_content_tag')) {
            Schema::create('content_page_content_tag', function (Blueprint $table) {
                $table->integer('content_page_id')->unsigned()->nullable();
                $table->foreign('content_page_id', 'fk_p_193323_193322_conten_5b6978f492879')->references('id')->on('content_pages')->onDelete('cascade');
                $table->integer('content_tag_id')->unsigned()->nullable();
                $table->foreign('content_tag_id', 'fk_p_193322_193323_conten_5b6978f492960')->references('id')->on('content_tags')->onDelete('cascade');
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
        Schema::dropIfExists('content_page_content_tag');
    }
}
