<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDeliveryCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_delivery_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->smallInteger('minimum_quantity')->default(1);
            $table->smallInteger('maximum_quantity')->nullable();
            $table->unsignedDecimal('north_central_cost')->nullable();
            $table->unsignedTinyInteger('north_central_delivery_days')->nullable();
            $table->unsignedDecimal('north_east_cost')->nullable();
            $table->unsignedTinyInteger('north_east_delivery_days')->nullable();
            $table->unsignedDecimal('north_west_cost')->nullable();
            $table->unsignedTinyInteger('north_west_delivery_days')->nullable();
            $table->unsignedDecimal('south_east_cost')->nullable();
            $table->unsignedTinyInteger('south_east_delivery_days')->nullable();
            $table->unsignedDecimal('south_west_cost')->nullable();
            $table->unsignedTinyInteger('south_west_delivery_days')->nullable();
            $table->unsignedDecimal('south_south_cost')->nullable();
            $table->unsignedTinyInteger('south_south_delivery_days')->nullable();
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_delivery_costs');
    }
}
