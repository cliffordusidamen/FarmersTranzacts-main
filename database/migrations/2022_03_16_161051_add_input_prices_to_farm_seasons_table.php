<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInputPricesToFarmSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farm_seasons', function (Blueprint $table) {
            $table->string('input_supply_prices', 1000)->nullable()->after('animals_sale');
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
            $table->dropColumn('input_supply_prices');
        });
    }
}
