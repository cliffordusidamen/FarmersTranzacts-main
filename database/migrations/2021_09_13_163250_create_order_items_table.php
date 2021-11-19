<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('vendor_order_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->unsignedDecimal('unit_amount', 15);
            $table->unsignedDecimal('total_amount', 15);
            $table->unsignedDecimal('delivery_amount')->default(0);
            $table->unsignedTinyInteger('delivery_days')->default(0);
            $table->unsignedDecimal('grand_total_amount', 15)->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('vendor_order_id')
                ->references('id')
                ->on('vendor_orders')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
