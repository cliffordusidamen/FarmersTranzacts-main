<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentProductCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_product_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedInteger('product_category_id');
            $table->timestamps();

            $table->foreign('agent_id')
                ->references('id')
                ->on('agents')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('product_category_id')
                ->references('id')
                ->on('product_categories')
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
        Schema::enableForeignKeyConstraints();
        Schema::dropIfExists('agent_product_category');
        Schema::disableForeignKeyConstraints();
    }
}
