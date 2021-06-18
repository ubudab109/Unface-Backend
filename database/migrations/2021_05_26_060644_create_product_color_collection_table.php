<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductColorCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_color_collection', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_size_id');
            $table->foreign('product_size_id')
                ->references('id')
                ->on('product_size_collection')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('color_id');
            $table->foreign('color_id')
                ->references('id')
                ->on('color')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('stock');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_color_collection');
    }
}
