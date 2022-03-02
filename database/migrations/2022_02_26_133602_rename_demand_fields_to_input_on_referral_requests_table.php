<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDemandFieldsToInputOnReferralRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_requests', function (Blueprint $table) {
            $table->renameColumn('seed_demand', 'seed_input')->change();
            $table->renameColumn('agro_chemical_demand', 'agro_chemical_input')->change();
            $table->renameColumn('animal_feed_demand', 'animal_feed_input')->change();
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
            $table->renameColumn('seed_input', 'seed_demand')->change();
            $table->renameColumn('agro_chemical_input', 'agro_chemical_demand')->change();
            $table->renameColumn('animal_feed_input', 'animal_feed_demand')->change();
        });
    }
}
