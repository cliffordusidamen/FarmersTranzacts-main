<?php

use App\Models\ReferralRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('middle_name', 25)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 15);
            $table->string('age', 2)->nullable();
            $table->string('address_line_1', 100);
            $table->string('address_line_2', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->unsignedMediumInteger('state_id')->nullable();
            $table->string('business_name')->nullable();
            $table->string('crops_grown', 1000)->nullable();
            $table->string('animals_reared', 1000)->nullable();
            $table->string('farm_size', 20)->nullable();
            $table->string('seed_demand', 500)->nullable();
            $table->string('fertilizer_demand', 500)->nullable();
            $table->string('agro_chemical_demand', 500)->nullable();
            $table->string('animal_feed_demand', 500)->nullable();
            $table->unsignedSmallInteger('number_of_workers')->nullable();
            $table->enum('approval_status', ReferralRequest::getStatuses())
                ->default(ReferralRequest::STATUS_PENDING);
            $table->string('disapproval_reason', 1000)->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('disapproved_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_requests');
    }
}
