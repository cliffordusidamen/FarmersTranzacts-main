<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmSeasonProducesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_season_produces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_season_id');
            $table->string('name', 255);
            $table->string('unit', 255)->nullable();
            $table->unsignedInteger('quantity_produced')->nullable();
            $table->unsignedInteger('quantity_sold')->nullable();
            $table->unsignedInteger('price_per_unit')->nullable();
            $table->timestamps();

            $table->foreign('farm_season_id')
                ->references('id')
                ->on('farm_seasons')
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
        Schema::dropIfExists('farm_season_produces');
    }
}
