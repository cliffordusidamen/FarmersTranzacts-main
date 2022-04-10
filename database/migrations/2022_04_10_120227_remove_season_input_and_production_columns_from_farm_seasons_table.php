<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSeasonInputAndProductionColumnsFromFarmSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farm_seasons', function (Blueprint $table) {
            $table->dropColumn('crops_harvest');
            $table->dropColumn('animals_harvest');
            $table->dropColumn('seed_input');
            $table->dropColumn('fertilizer_bags');
            $table->dropColumn('agro_chemical_input');
            $table->dropColumn('animal_feed_input');
            $table->dropColumn('crops_sale');
            $table->dropColumn('animals_sale');
            $table->dropColumn('input_supply_prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farm_seasons', function (Blueprint $table) {
            $table->string('crops_harvest', 1000)->nullable()->after('farm_size');
            $table->string('animals_harvest', 1000)->nullable()->after('farm_size');
            $table->string('animal_feed_input', 500)->nullable()->after('farm_size');
            $table->string('seed_input', 500)->nullable()->after('farm_size');
            $table->string('agro_chemical_input', 500)->nullable()->after('farm_size');
            $table->string('fertilizer_bags', 100)->nullable()->after('farm_size');
            $table->string('crops_sale', 1000)->nullable()->after('farm_size');
            $table->string('animals_sale', 1000)->nullable()->after('farm_size');
            $table->string('input_supply_prices', 1000)->nullable()->after('animals_sale');
        });
    }
}
