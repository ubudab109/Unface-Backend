<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialIdAndDiscountOnProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->unsignedInteger('material_id')->after('catalogue_id');
            $table->foreign('material_id')
                ->references('id')
                ->on('material')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->double('discount')->nullable()->after('description');
            $table->enum("discount_type", ['fixed', 'percent'])->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('material_id');
            $table->dropColumn('discount');
            $table->dropColumn('discount_type');
        });
    }
}
