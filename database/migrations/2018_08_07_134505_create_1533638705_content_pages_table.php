<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create1533638705ContentPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('content_pages')) {
            Schema::create('content_pages', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->text('page_text')->nullable();
                $table->text('excerpt')->nullable();
                $table->string('featured_image')->nullable();

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
        Schema::dropIfExists('content_pages');
    }
}
