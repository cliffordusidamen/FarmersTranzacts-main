<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeasonNotesToFarmSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farm_seasons', function (Blueprint $table) {
            $table->text('season_notes')->nullable()->after('animals_sale');
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
            $table->dropColumn('season_notes');
        });
    }
}
