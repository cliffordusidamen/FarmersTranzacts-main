<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkersPayToReferralRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_requests', function (Blueprint $table) {
            $table->string('workers_pay', 1000)->nullable()->after('animals_sale');
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
            $table->dropColumn('workers_pay');
        });
    }
}
