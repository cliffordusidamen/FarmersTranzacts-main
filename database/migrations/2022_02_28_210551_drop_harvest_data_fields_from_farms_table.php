<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropHarvestDataFieldsFromFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->dropColumn('number_of_farmers');
            $table->dropColumn('farm_size');
            $table->dropColumn('crops');
            $table->dropColumn('animals');
            $table->dropColumn('seed_demand');
            $table->dropColumn('fertilizer_demand');
            $table->dropColumn('agro_chemical_demand');
            $table->dropColumn('animal_feed_demand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->unsignedInteger('number_of_farmers')->nullable();
            $table->string('farm_size', 500)->nullable();
            $table->string('crops', 500)->nullable();
            $table->string('animals', 500)->nullable();
            $table->string('seed_demand', 500)->nullable();
            $table->string('fertilizer_bags', 500)->nullable();
            $table->string('agro_chemical_demand', 500)->nullable();
            $table->string('animal_feed_demand', 500)->nullable();
        });
    }
}
