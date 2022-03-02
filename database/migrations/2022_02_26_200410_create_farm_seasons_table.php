<?php

use App\Models\FarmSeason;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->enum('season', FarmSeason::getSeasons());
            $table->year('year');
            $table->string('farm_size', 20)->nullable();
            $table->string('crops_harvest', 1000)->nullable();
            $table->string('animals_harvest', 1000)->nullable();
            $table->string('seed_input', 500)->nullable();
            $table->string('fertilizer_bags', 500)->nullable();
            $table->string('agro_chemical_input', 500)->nullable();
            $table->string('animal_feed_input', 500)->nullable();
            $table->unsignedSmallInteger('number_of_workers')->nullable();
            $table->string('crops_sale', 1000)->nullable();
            $table->string('animals_sale', 1000)->nullable();
            $table->timestamps();

            $table->foreign('farm_id')
                ->references('id')
                ->on('farms')
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
        Schema::dropIfExists('farm_seasons');
    }
}
