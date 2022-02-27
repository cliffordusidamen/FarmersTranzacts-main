<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFertilizerBagsColumnToIntegerOnReferralRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_requests', function (Blueprint $table) {
            $table->unsignedInteger('fertilizer_bags')
                ->comment('Fertilizer input in bags')
                ->change();
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
            $table->string('fertilizer_bags', 500)->comment('')->nullable()->change();
        });
    }
}
