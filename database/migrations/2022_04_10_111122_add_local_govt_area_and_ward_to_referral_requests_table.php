<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalGovtAreaAndWardToReferralRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_requests', function (Blueprint $table) {
            $table->string('ward', 100)->nullable()->after('state_id');

            $table->unsignedMediumInteger('local_government_area_id')->nullable()->after('state_id');

            $table->foreign('local_government_area_id')
                ->references('id')
                ->on('local_government_areas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
            $table->dropForeign(['local_government_area_id']);
            $table->dropColumn('local_government_area_id');

            $table->dropColumn('ward');
        });
    }
}
