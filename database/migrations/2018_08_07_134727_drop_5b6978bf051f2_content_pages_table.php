<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Drop5b6978bf051f2ContentPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('content_pages');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(! Schema::hasTable('content_pages')) {
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
}
