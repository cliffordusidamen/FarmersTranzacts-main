<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHarvestColumnsToReferralRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_requests', function (Blueprint $table) {
            $table->renameColumn('animals_reared', 'animals_harvest')->change();
            $table->renameColumn('crops_grown', 'crops_harvest')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_requests', function (Blueprint $table) {
            $table->renameColumn('animals_harvest', 'animals_reared')->change();
            $table->renameColumn('crops_harvest', 'crops_grown')->change();
        });
    }
}
