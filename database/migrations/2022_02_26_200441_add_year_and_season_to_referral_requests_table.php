<?php

use App\Models\FarmSeason;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearAndSeasonToReferralRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_requests', function (Blueprint $table) {
            $table->year('season_year')
                ->comment('Most recent year of the season')
                ->after('number_of_workers');

            $table->enum('season', FarmSeason::getSeasons())
                ->after('season_year');
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
            $table->dropColumn('season_year');
            $table->dropColumn('season');
        });
    }
}
